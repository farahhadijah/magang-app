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
        $statusKrs = $siakadService->hasKrsValidasi(
            $mahasiswa->nim
        );
        if ($statusKrs === null) {
            return redirect()
                ->route('dashboard')
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