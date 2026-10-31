<?php

namespace App\Http\Middleware;

use App\Services\SiakadService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BebasNilaiDE
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $mahasiswa = auth()->user()?->mahasiswa;
        if (! $mahasiswa) {
            abort(403);
        }
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
                    'SIAKAD sedang bermasalah. Silakan coba kembali nanti.'
                );
        }
        $punyaNilaiDE = $siakadService->hasNilaiDE(
            $mahasiswa->nim
        );
        if (! $statusKrs || $punyaNilaiDE) {
            return redirect()
                ->route('mahasiswa.remedial.index');
        }
        return $next($request);
    }
}