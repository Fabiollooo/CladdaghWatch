<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ── 1. X-Frame-Options ─────────────────────────────────────────────
        // Prevents this page being silently embedded in an <iframe> on another
        // domain (clickjacking). SAMEORIGIN allows framing only by pages on
        // the same origin (e.g. our own admin dashboard).
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // ── 2. X-Content-Type-Options ──────────────────────────────────────
        // Stops the browser from "sniffing" a file's content type.
        // Without this, a browser might execute an uploaded file as JavaScript
        // even if the server sent it as text/plain.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── 3. Strict-Transport-Security (HSTS) ────────────────────────────
        // After the first HTTPS visit, the browser will never send a plain
        // HTTP request to this domain for the next year (31 536 000 seconds),
        // preventing SSL-strip attacks.
        // includeSubDomains extends the rule to every subdomain.
        // NOTE: only honoured by browsers when the response is served over
        // HTTPS — safe to send on HTTP but browsers will ignore it.
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains'
        );

        // ── 4. Content-Security-Policy ─────────────────────────────────────
        // Whitelists exactly which origins the browser may load each resource
        // type from. Anything not on the list is blocked, which limits the
        // damage an XSS injection can do.
        //
        // Directives used:
        //   default-src     - fallback for unlisted resource types
        //   script-src      - JavaScript sources
        //   style-src       - CSS sources
        //   font-src        - web-font sources
        //   img-src         - image sources (data: allows base64 inline images)
        //   connect-src     - fetch() / XHR destinations
        //   frame-ancestors - who may embed THIS page (CSP twin of X-Frame-Options)
        //   base-uri        - prevents <base href> hijacking
        //   form-action     - where <form> elements may submit to
        //
        // 'unsafe-inline' on script-src is required because the views use
        // inline <script> blocks. The next step to harden this further is to
        // replace 'unsafe-inline' with per-request nonces:
        //   script-src 'nonce-{random}' 'strict-dynamic'
        // and add that nonce to every <script> tag in the views.
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "font-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.gstatic.com",
            "img-src 'self' data:",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}