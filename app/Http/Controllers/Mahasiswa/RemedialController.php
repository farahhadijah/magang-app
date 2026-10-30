<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\FormulirRemedial;
use App\Services\SiakadService;
use Illuminate\Support\Facades\Auth;

class RemedialController extends Controller
{
    public function index(SiakadService $siakadService)
    {
        $mahasiswa = Auth::user()?->mahasiswa;

        abort_if(! $mahasiswa, 404);

        // Pastikan API SIAKAD dapat diakses.
        if (! $siakadService->isApiAvailable($mahasiswa->nim)) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Server SIAKAD sedang bermasalah. Silakan coba lagi nanti.'
                );
        }

        // Periksa KRS pada semester validasi.
        $statusKrs = $siakadService->hasKrsValidasi(
            $mahasiswa->nim
        );

        // null berarti pemeriksaan KRS gagal karena masalah API.
        if ($statusKrs === null) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Validasi akademik tidak dapat dilakukan. Silakan coba lagi nanti.'
                );
        }

        // Ambil daftar mata kuliah dengan nilai D/E.
        $matakuliahRemedial = $siakadService
            ->getNilaiBermasalah($mahasiswa->nim);

        $matakuliahRemedial = is_array($matakuliahRemedial)
            ? $matakuliahRemedial
            : [];

        // Ambil formulir remedial sesuai fakultas mahasiswa.
        $formulirRemedial = null;

        $fakultasId = $mahasiswa->prodi?->fakultas_id;

        if (! empty($matakuliahRemedial) && $fakultasId) {
            $formulirRemedial = FormulirRemedial::where(
                'fakultas_id',
                $fakultasId
            )
                ->latest()
                ->first();
        }

        // Informasi semester untuk ditampilkan pada halaman.
        $tahunSmsAktif = $siakadService->getTahunSemesterAktif();

        $tahunSmsValidasi = $siakadService
            ->getTahunSemesterValidasi();

        return view('mahasiswa.remedial.index', compact(
            'mahasiswa',
            'statusKrs',
            'tahunSmsAktif',
            'tahunSmsValidasi',
            'matakuliahRemedial',
            'formulirRemedial'
        ));
    }
}
