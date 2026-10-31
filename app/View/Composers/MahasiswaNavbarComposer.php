<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\SiakadService;

class MahasiswaNavbarComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        if (!$user || !$user->mahasiswa) {
            return;
        }

        $mahasiswa = $user->mahasiswa;

        $pengajuan = $mahasiswa
            ->pengajuanPkl()
            ->latest()
            ->first();

        $pkl = $pengajuan?->pkl;

        $siakadService = app(SiakadService::class);

        $siakadAktif = $siakadService
            ->isApiAvailable($mahasiswa->nim);

        $punyaNilaiDE = false;
        $belumKrs = false;

        if ($siakadAktif) {
            // getNilaiMahasiswa() di-cache 10 menit, jadi dua panggilan
            // di bawah tidak menghasilkan request dobel ke SIAKAD.
            $punyaNilaiDE = $siakadService
                ->hasNilaiDE($mahasiswa->nim);

            $belumKrs = ! $siakadService
                ->hasKrsForValidasi($mahasiswa->nim);
        }

        $view->with([
            'navbarPengajuan' => $pengajuan,
            'navbarPkl' => $pkl,
            'navbarSiakadAktif' => $siakadAktif,
            'navbarPunyaNilaiDE' => $punyaNilaiDE,
            'navbarBelumKrs' => $belumKrs,
            'navbarBolehUploadLaporan' => $pkl?->isSiapUploadLaporan(),
            'navbarPenilaianMitra' => $pkl?->penilaianMitra,
        ]);
    }
}