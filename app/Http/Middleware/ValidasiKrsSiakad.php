<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SiakadService;

class ValidasiKrsSiakad
{
    public function handle(Request $request, Closure $next): Response
    {
        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            abort(403);
        }

        $siakadService = app(SiakadService::class);

        // API down -> arahkan ke Status Akademik, bukan blokir permanen
        if (! $siakadService->isApiAvailable($mahasiswa->nim)) {
            return redirect()
                ->route('mahasiswa.status-akademik.index')
                ->with(
                    'error',
                    'Server SIAKAD sedang bermasalah. Silakan coba lagi nanti.'
                );
        }

        if (! $siakadService->hasKrsForValidasi($mahasiswa->nim)) {
            return redirect()
                ->route('mahasiswa.status-akademik.index')
                ->with(
                    'error',
                    'Anda belum melakukan KRS pada semester validasi. Pengajuan PKL tidak dapat diproses.'
                );
        }

        return $next($request);
    }
}