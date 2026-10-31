<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\FormulirRemedial;
use App\Services\SiakadService;
use Illuminate\Support\Facades\Auth;

class StatusAkademikController extends Controller
{
    public function index(SiakadService $siakadService)
    {
        $mahasiswa = Auth::user()?->mahasiswa;
        abort_if(! $mahasiswa, 404);

        /**
         * Jika API down -> arahkan ke dashboard dengan pesan.
         * Halaman Status Akademik tidak bisa menampilkan data apapun
         * tanpa API, jadi tidak ada gunanya dirender.
         */
        if (! $siakadService->isApiAvailable($mahasiswa->nim)) {
            return redirect()
                ->route('mahasiswa.dashboard')
                ->with(
                    'error',
                    'Server SIAKAD sedang bermasalah. Silakan coba lagi nanti.'
                );
        }

        // --- Cek nilai D/E ---
        $matakuliahRemedial = $siakadService->getNilaiBermasalah($mahasiswa->nim);
        $punyaNilaiDE = count($matakuliahRemedial) > 0;

        // --- Cek KRS semester validasi ---
        $sudahKrs = $siakadService->hasKrsForValidasi($mahasiswa->nim);
        $belumKrs = ! $sudahKrs;

        /**
         * Kalau dua-duanya aman (tidak ada D/E dan sudah KRS),
         * tidak ada masalah akademik -> langsung ke pengajuan.
         */
        if (! $punyaNilaiDE && ! $belumKrs) {
            return redirect()
                ->route('mahasiswa.pengajuan.create')
                ->with('success', 'Status akademik Anda aman. Silakan lanjutkan pengajuan PKL.');
        }

        // --- Formulir remedial (hanya kalau ada D/E) ---
        $formulirRemedial = null;
        if ($punyaNilaiDE) {
            $fakultasId = $mahasiswa->prodi->fakultas_id;
            $formulirRemedial = FormulirRemedial::where('fakultas_id', $fakultasId)
                ->latest()
                ->first();
        }

        return view('mahasiswa.status-akademik.index', compact(
            'mahasiswa',
            'matakuliahRemedial',
            'punyaNilaiDE',
            'belumKrs',
            'sudahKrs',
            'formulirRemedial'
        ));
    }
}