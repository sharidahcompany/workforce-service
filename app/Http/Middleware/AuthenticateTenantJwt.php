<?php

namespace App\Http\Middleware;

use App\Models\Central\Tenant\Tenant;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateTenantJwt
{
    public function handle(Request $request, Closure $next)
    {
        try {

            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json([
                    'message' => 'd.',
                ], 401);
            }

            auth('api')->setUser($user);

            return $next($request);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
}
