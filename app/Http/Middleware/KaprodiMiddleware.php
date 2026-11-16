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

        // Cek hak akses dosen
        if ($user->role === 'dosen') {
            // Kaprodi memiliki akses ke seluruh area kaprodi
            if ($user->isKaprodi()) {
                return $next($request);
            }

            // Dekan & Wadek diizinkan mengakses menu monitoring mahasiswa (Mahasiswa PKL & Mahasiswa Belum PKL)
            if ($user->isPimpinanFakultas() && $request->routeIs('kaprodi.mahasiswa.*')) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
    }
}