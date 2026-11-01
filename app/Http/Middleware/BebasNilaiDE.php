<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SiakadService;

class BebasNilaiDE
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $mahasiswa = auth()->user()?->mahasiswa;

        /**
         * Jika bukan mahasiswa
         */
        if (!$mahasiswa) {
            abort(403);
        }

        /**
         * Cek nilai D/E dari API SIAKAD
         */
        $siakadService = app(SiakadService::class);

        // API down -> jangan blokir dengan pesan D/E, arahkan ke Status Akademik
        if (! $siakadService->isApiAvailable($mahasiswa->nim)) {
            return redirect()
                ->route('mahasiswa.status-akademik.index')
                ->with(
                    'error',
                    'Server SIAKAD sedang bermasalah. Silakan coba lagi nanti.'
                );
        }

        if ($siakadService->hasNilaiDE($mahasiswa->nim)) {

            return redirect()
                ->route('mahasiswa.status-akademik.index')
                ->with(
                    'error',
                    'Anda masih memiliki nilai D/E dan wajib remedial terlebih dahulu.'
                );
        }

        return $next($request);
    }
}