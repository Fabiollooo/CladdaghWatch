<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PreventSqlInjection
{
    // Fields that are never scanned — passwords go straight to Hash::make(),
    // never into a query, so scanning them only risks false positives.
    private array $skipFields = [
        'password',
        'password_confirmation',
        'current_password',
        'token',
    ];

    // Each pattern targets a specific, well-known injection technique.
    // Patterns are intentionally multi-token so that innocent words like
    // "select" in a sentence, or apostrophes in names like O'Brien, are
    // not blocked.
    private array $patterns = [

        // ── Classic statement injection ────────────────────────────────────
        // These require two keywords together, which almost never appears in
        // legitimate user input.
        '/\bunion\b.+\bselect\b/i',                    // UNION SELECT
        '/\bselect\b.+\bfrom\b/i',                     // SELECT ... FROM
        '/\binsert\b.+\binto\b/i',                     // INSERT INTO
        '/\bupdate\b.+\bset\b/i',                      // UPDATE ... SET
        '/\bdelete\b.+\bfrom\b/i',                     // DELETE FROM
        '/\bdrop\b.+\b(table|database|schema)\b/i',    // DROP TABLE / DATABASE
        '/\btruncate\b.+\btable\b/i',                  // TRUNCATE TABLE
        '/\balter\b.+\btable\b/i',                     // ALTER TABLE
        '/\bcreate\b.+\btable\b/i',                    // CREATE TABLE

        // ── Boolean-based blind injection ──────────────────────────────────
        // Patterns like ' OR '1'='1 or OR 1=1 that make a WHERE clause
        // always evaluate to true.
        '/\b(or|and)\b\s+\d+\s*=\s*\d+/i',            // OR 1=1 / AND 1=1
        '/\b(or|and)\b\s+[\'"][^\'"]+[\'"]=[\'"][^\'"]+[\'"]/i', // OR 'a'='a'
        '/(\'|")\s*(or|and)\s+[\'"0-9]/i',             // ' OR 1 / ' OR '

        // ── Time-based blind injection ──────────────────────────────────────
        // Attackers use SLEEP() or BENCHMARK() to infer data by measuring
        // how long the server takes to respond.
        '/\bsleep\s*\(\s*\d+/i',                       // SLEEP(5)
        '/\bbenchmark\s*\(\s*\d+/i',                   // BENCHMARK(1000000,...)
        '/\bwaitfor\b.+\bdelay\b/i',                   // WAITFOR DELAY (MSSQL)

        // ── File read / write ──────────────────────────────────────────────
        // These MySQL functions can read server files or write data to disk.
        '/\bload_file\s*\(/i',                          // LOAD_FILE(...)
        '/\binto\s+(outfile|dumpfile)\b/i',             // INTO OUTFILE / DUMPFILE

        // ── Stacked queries ────────────────────────────────────────────────
        // A semicolon followed by another SQL statement attempts to run a
        // second query after the legitimate one.
        '/;\s*(select|insert|update|delete|drop|truncate|alter|create)\b/i',

        // ── Inline SQL comments ────────────────────────────────────────────
        // -- and /* */ are used to strip the remainder of a query, e.g.
        //   ' OR 1=1 --    (neutralises a closing quote or condition)
        '/--\s/i',                                      // -- (double-dash comment)
        '/\/\*[\s\S]*?\*\//i',                          // /* block comment */

        // ── Dangerous stored procedures ────────────────────────────────────
        '/\bxp_cmdshell\b/i',                           // MSSQL shell execution
        '/\bsp_executesql\b/i',                         // MSSQL dynamic SQL

        // ── Encoding tricks ────────────────────────────────────────────────
        // Attackers sometimes hex-encode keywords to bypass naive string filters.
        // Four or more hex digits strongly suggests an encoded payload.
        '/0x[0-9a-fA-F]{4,}/i',                        // 0x414243...

        // ── CHAR() / string construction ───────────────────────────────────
        // CHAR(65,66,67) builds strings character-by-character to bypass
        // keyword filters.
        '/\bchar\s*\(\s*\d{1,3}\s*(,\s*\d{1,3}\s*){2,}\)/i',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $inputs = $this->flattenInputs($request->all());

        foreach ($inputs as $field => $value) {
            // Only scan string values; skip non-string types (ints, booleans…)
            if (!is_string($value) || $value === '') {
                continue;
            }

            // Skip sensitive fields that never touch the database as raw input
            $baseField = last(explode('.', $field));
            if (in_array(strtolower($baseField), $this->skipFields, true)) {
                continue;
            }

            foreach ($this->patterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    Log::warning('SQL injection attempt blocked', [
                        'ip'      => $request->ip(),
                        'method'  => $request->method(),
                        'url'     => $request->fullUrl(),
                        'field'   => $field,
                        'pattern' => $pattern,
                        // Truncate the logged value so the log itself is not
                        // exploitable or bloated by a large payload.
                        'value'   => mb_substr($value, 0, 200),
                    ]);

                    // Return JSON for API requests, redirect for web requests
                    if ($request->expectsJson() || $request->is('api/*')) {
                        return response()->json(
                            ['message' => 'Invalid input detected.'],
                            400
                        );
                    }

                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'Invalid input detected.']);
                }
            }
        }

        return $next($request);
    }

    // Recursively flattens nested input arrays into dot-notation keys so that
    // every scalar value is scanned regardless of nesting depth.
    // e.g. ['user' => ['name' => 'test']]  →  ['user.name' => 'test']
    private function flattenInputs(array $inputs, string $prefix = ''): array
    {
        $flat = [];

        foreach ($inputs as $key => $value) {
            $fullKey = $prefix !== '' ? "{$prefix}.{$key}" : (string)$key;

            if (is_array($value)) {
                $flat = array_merge($flat, $this->flattenInputs($value, $fullKey));
            } else {
                $flat[$fullKey] = $value;
            }
        }

        return $flat;
    }
}