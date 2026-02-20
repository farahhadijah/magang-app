<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if (!$user->is_active) {
            abort(403, 'Akun tidak aktif');
        }

        if (!in_array($user->role, $roles)) {
            // Log detail untuk debugging mengapa akses ditolak
            Log::warning('RoleMiddleware: akses ditolak', [
                'user_id' => $user->id ?? null,
                'role' => $user->role,
                'expected_roles' => $roles,
                'is_active' => $user->is_active,
                'path' => $request->path(),
            ]);
            abort(403, 'Forbidden - Role tidak memiliki akses');
        }

        return $next($request);
    }
}