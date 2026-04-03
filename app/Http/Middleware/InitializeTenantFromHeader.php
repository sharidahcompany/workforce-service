<?php

namespace App\Http\Middleware;

use App\Models\Central\Tenant\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantFromHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant');

        if (!$tenantId) {
            return response()->json([
                'message' => 'Missing tenant header.',
            ], 400);
        }

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return response()->json([
                'message' => 'Invalid tenant identifier.',
            ], 400);
        }

        tenancy()->initialize($tenant);

        return $next($request);
    }
}
