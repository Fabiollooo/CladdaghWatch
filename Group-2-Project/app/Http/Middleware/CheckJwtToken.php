<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\JwtHelper;

class CheckJwtToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user has a valid JWT token
        $payload = JwtHelper::fromCookie();
        
        if (!$payload) {
            // No valid JWT token, redirect to login
            return redirect('/login');
        }

        $role = (int)($payload['role'] ?? 99);
        if ($role === 4 && !$request->is('supervisor/calendar')) {
            return redirect('/supervisor/calendar');
        }
        
        // Token is valid, allow the request to proceed
        return $next($request);
    }
}
