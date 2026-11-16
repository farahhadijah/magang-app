<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\PengajuanPkl;
use App\Models\Pkl;
use App\Models\LaporanAkhir;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen = auth()->user()->dosen;
        $dosenId = $dosen->id;

        /* ================= STATISTIK DOSEN ================= */

        $mahasiswaCount = Pkl::where('id_dosen', $dosenId)
            ->where('status', 'aktif')
            ->count();

        $logbookPendingCount = Logbook::whereHas('pkl', function ($q) use ($dosenId) {
                $q->where('id_dosen', $dosenId)
                  ->where('status', 'aktif');
            })
            ->where('status_approve', 'pending')
            ->count();

        $laporanAkhirCount = LaporanAkhir::whereHas('pkl', function ($q) use ($dosenId) {
            $q->where('id_dosen', $dosenId)
              ->where('status', 'aktif');
        })
        ->where('status_approve', 'pending')
        ->count();

        /* ================= CEK JABATAN DOSEN ================= */

        $isKaprodi = $dosen && strtolower($dosen->jabatan) === 'kaprodi';
        $isPimpinanFakultas = $dosen && in_array(strtolower($dosen->jabatan), ['dekan', 'wadek']);

        $totalMenunggu = 0;
        $totalAktif = 0;
        $totalBelumPkl = 0;
        $totalResumeFakultas = 0;

        if ($isKaprodi) {
            $prodiId = $dosen->prodi_id;

            // Menunggu verifikasi kaprodi
            $totalMenunggu = PengajuanPkl::where('status', 'pending_kaprodi')
                ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                    $q->where('prodi_id', $prodiId);
                })
                ->count();

            // PKL aktif
            $totalAktif = Pkl::where('status', 'aktif')
                ->whereHas('pengajuan', function ($q) use ($prodiId) {
                    $q->whereHas('mahasiswa', function ($q2) use ($prodiId) {
                        $q2->where('prodi_id', $prodiId);
                    });
                })
                ->count();
        } elseif ($isPimpinanFakultas) {
            $fakultasId = $dosen->prodi?->fakultas_id;

            // PKL aktif satu fakultas
            $totalAktif = Pkl::where('status', 'aktif')
                ->whereHas('pengajuanPkl.mahasiswa.prodi', function ($q) use ($fakultasId) {
                    $q->where('fakultas_id', $fakultasId);
                })
                ->count();

            // Mahasiswa belum mengajukan PKL satu fakultas
            $totalBelumPkl = \App\Models\Mahasiswa::whereHas('prodi', function ($q) use ($fakultasId) {
                    $q->where('fakultas_id', $fakultasId);
                })
                ->whereDoesntHave('pengajuanPkl', function ($q) {
                    $q->whereNotIn('status', ['ditolak_tu', 'ditolak_kaprodi']);
                })
                ->count();

            // Total mahasiswa PKL selesai satu fakultas
            $totalResumeFakultas = Pkl::where('status', 'selesai')
                ->whereHas('pengajuanPkl.mahasiswa.prodi', function ($q) use ($fakultasId) {
                    $q->where('fakultas_id', $fakultasId);
                })
                ->count();
        }

        return view('dosen.dashboard', compact(
            'isKaprodi',
            'isPimpinanFakultas',
            'mahasiswaCount',
            'logbookPendingCount',
            'laporanAkhirCount',
            'totalMenunggu',
            'totalAktif',
            'totalBelumPkl',
            'totalResumeFakultas'
        ));
    }
}