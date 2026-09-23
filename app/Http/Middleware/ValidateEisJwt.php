<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateEisJwt
{
    /**
     * Handle an incoming request.
     * Validates JWT B (Fastify -> EIS) for service-to-service communication.
     */
    public function handle(Request $request, Closure $next, ?string $requiredScope = null): Response
    {
        $authHeader = $request->header('Authorization', '');

        if (!str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Missing or malformed Bearer token',
            ], 401);
        }

        $token = substr($authHeader, 7);
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid token format',
            ], 401);
        }

        [$b64Header, $b64Payload, $b64Signature] = $parts;

        $secret = env('EIS_JWT_SECRET', 'fastify-eis-secret-key-super-secure-token-b');
        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', "{$b64Header}.{$b64Payload}", $secret, true)
        );

        if (!hash_equals($expectedSignature, $b64Signature)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid token signature',
            ], 401);
        }

        $payload = json_decode($this->base64UrlDecode($b64Payload), true);

        if (!is_array($payload)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Corrupted token payload',
            ], 401);
        }

        // Verify Expiration
        if (isset($payload['exp']) && time() >= $payload['exp']) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Token has expired',
            ], 401);
        }

        // Verify Issuer
        $expectedIssuer = env('EIS_JWT_ISSUER', 'integration-service');
        if (isset($payload['iss']) && $payload['iss'] !== $expectedIssuer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid token issuer',
            ], 401);
        }

        // Verify Audience
        $expectedAudience = env('EIS_JWT_AUDIENCE', 'eis');
        if (isset($payload['aud']) && $payload['aud'] !== $expectedAudience) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid token audience',
            ], 401);
        }

        // Verify Required Scope if specified
        if ($requiredScope !== null && $requiredScope !== '') {
            $tokenScopes = $payload['scope'] ?? [];
            if (is_string($tokenScopes)) {
                $tokenScopes = preg_split('/\s+/', trim($tokenScopes));
            }

            if (!is_array($tokenScopes) || !in_array($requiredScope, $tokenScopes, true)) {
                return response()->json([
                    'success' => false,
                    'message' => "Forbidden: Insufficient scope. Required: '{$requiredScope}'",
                ], 403);
            }
        }

        $request->attributes->set('jwt_claims', $payload);

        return $next($request);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
