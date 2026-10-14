<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Http\Request;

trait UserScopedQuery
{
    /**
     * Check if request is from microservice (JWT B) or system admin / service account.
     */
    protected function isServiceOrAdmin(Request $request): bool
    {
        // 1. JWT B Claims set by ValidateEisJwt middleware (/api/v1/...)
        if ($request->attributes->has('jwt_claims')) {
            return true;
        }

        // 2. Direct Bearer token check for JWT B in case called directly on endpoints
        $authHeader = $request->header('Authorization', '');
        if (str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            $parts = explode('.', $token);
            if (count($parts) === 3) {
                [$b64Header, $b64Payload, $b64Signature] = $parts;
                $secret = env('EIS_JWT_SECRET', 'fastify-eis-secret-key-super-secure-token-b');
                $expectedSig = rtrim(strtr(base64_encode(hash_hmac('sha256', "{$b64Header}.{$b64Payload}", $secret, true)), '+/', '-_'), '=');
                if (hash_equals($expectedSig, $b64Signature)) {
                    $payload = json_decode(base64_decode(strtr($b64Payload, '-_', '+/')), true);
                    if (is_array($payload) && (!isset($payload['exp']) || time() < $payload['exp'])) {
                        return true;
                    }
                }
            }
        }

        // 3. User with username 'admin' or 'integration-service'
        $user = $this->resolveUser($request);
        if ($user && in_array($user->username, ['admin', 'integration-service'], true)) {
            return true;
        }

        return false;
    }

    /**
     * Resolve the current authenticated user across Sanctum, Web session, or request.
     */
    protected function resolveUser(Request $request): ?User
    {
        return $request->user('sanctum') 
            ?? auth('sanctum')->user() 
            ?? auth('web')->user() 
            ?? $request->user();
    }
}
