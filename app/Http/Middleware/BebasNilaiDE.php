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

        if ($siakadService->hasNilaiDE($mahasiswa->nim)) {

            return redirect()
                ->route('mahasiswa.remedial.index')
                ->with(
                    'error',
                    'Anda masih memiliki nilai D/E dan wajib remedial terlebih dahulu.'
                );
        }

        return $next($request);
    }
}