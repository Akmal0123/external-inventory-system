<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptionalApiAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jwtEnabled = filter_var(env('API_JWT_ENABLED', false), FILTER_VALIDATE_BOOLEAN);

        // If JWT auth is disabled (for initial development/testing), bypass check
        if (!$jwtEnabled) {
            return $next($request);
        }

        // If enabled, verify bearer token via Sanctum or authenticated web session
        if (!auth('sanctum')->check() && !auth()->guard('web')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Silakan login terlebih dahulu.',
            ], 401);
        }

        return $next($request);
    }
}
