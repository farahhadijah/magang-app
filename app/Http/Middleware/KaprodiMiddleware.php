<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KaprodiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // pastikan login
        if (!$user) {
            abort(403);
        }

        // cek apakah dosen dan jabatan kaprodi (pakai helper model yang case-insensitive)
        if ($user->role === 'dosen' && $user->isKaprodi()) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses sebagai Kaprodi');
    }
}