<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\PengajuanPkl;
use App\Models\Pkl;

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

        $pklSelesaiCount = Pkl::where('id_dosen', $dosenId)
            ->where('status', 'selesai')
            ->count();

        /* ================= CEK KAPRODI ================= */

        $isKaprodi = $dosen && strtolower($dosen->jabatan) === 'kaprodi';

        $totalMahasiswa = 0;
        $totalMenunggu = 0;
        $totalAktif = 0;
        $totalSelesai = 0;

        if ($isKaprodi) {

            $prodiId = $dosen->prodi_id;

            $totalMahasiswa = PengajuanPkl::whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })->count();

            $totalMenunggu = PengajuanPkl::where('status','pending_kaprodi')
                ->whereHas('mahasiswa', fn($q)=>$q->where('prodi_id',$prodiId))
                ->count();

            $totalAktif = Pkl::where('status','aktif')
                ->whereHas('pengajuan.mahasiswa', fn($q)=>$q->where('prodi_id',$prodiId))
                ->count();

            $totalSelesai = Pkl::where('status','selesai')
                ->whereHas('pengajuan.mahasiswa', fn($q)=>$q->where('prodi_id',$prodiId))
                ->count();
        }

        return view('dosen.dashboard', compact(
            'isKaprodi',

            // statistik dosen
            'mahasiswaCount',
            'logbookPendingCount',
            'pklSelesaiCount',

            // statistik kaprodi
            'totalMahasiswa',
            'totalMenunggu',
            'totalAktif',
            'totalSelesai'
        ));
    }
}