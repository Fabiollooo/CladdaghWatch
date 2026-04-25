<?php

namespace App\Helpers;

class JwtHelper
{
    private static function secret(): string
    {
        // Use APP_KEY stripped of "base64:" prefix; fall back to a fixed string
        $key = config('app.key', 'claddagh-watch-patrol-jwt-secret');
        return str_starts_with($key, 'base64:') ? base64_decode(substr($key, 7)) : $key;
    }

    private static function b64u(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function b64uDecode(string $data): string
    {
        $pad = str_repeat('=', (4 - strlen($data) % 4) % 4);
        return base64_decode(strtr($data . $pad, '-_', '+/'));
    }

    /** Encode a payload array to a signed JWT string. */
    public static function encode(array $payload): string
    {
        $header  = self::b64u(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $body    = self::b64u(json_encode($payload));
        $sig     = self::b64u(hash_hmac('sha256', "$header.$body", self::secret(), true));
        return "$header.$body.$sig";
    }

    /**
     * Decode and verify a JWT string.
     * Returns the payload array on success, null on failure / expiry / tamper.
     */
    public static function decode(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        [$header, $body, $sig] = $parts;

        $expected = self::b64u(hash_hmac('sha256', "$header.$body", self::secret(), true));
        if (!hash_equals($expected, $sig)) return null;

        $data = json_decode(self::b64uDecode($body), true);
        if (!is_array($data)) return null;

        // Check expiry
        if (isset($data['exp']) && time() > $data['exp']) return null;

        return $data;
    }

    /** Read the JWT from the cw_jwt cookie; returns payload or null. */
    public static function fromCookie(): ?array
    {
        $raw = $_COOKIE['cw_jwt'] ?? null;
        if (!$raw) return null;
        return self::decode(urldecode($raw));
    }

    /** Convenience: return the role number from the current cookie JWT, or 99. */
    public static function role(): int
    {
        $payload = self::fromCookie();
        return $payload ? (int)($payload['role'] ?? 99) : 99;
    }

    /** True if the current user is ADMIN (1) or MANAGER (2). */
    public static function isAdmin(): bool
    {
        return in_array(self::role(), [1, 2], true);
    }

    /** True if any valid JWT cookie is present. */
    public static function isLoggedIn(): bool
    {
        return self::fromCookie() !== null;
    }
}
