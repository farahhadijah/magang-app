<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pkl;
use App\Models\TugasMitra;
use App\Models\TugasMitraSubmit;

class MitraController extends Controller
{
    public function mahasiswa()
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        $pkls = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->with(['mahasiswa.user'])
            ->get();

        return view('mitra.mahasiswa', compact('pkls'));
    }

    public function logbook($pklId)
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        $pkl = Pkl::where('id', $pklId)
            ->whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->with([
                'mahasiswa.user',
                'logbooks' => function ($query) {
                    $query->orderBy('tgl', 'asc');
                }
            ])
            ->firstOrFail();

        return view('mitra.logbook', compact('pkl'));
    }

    /**
     * List mahasiswa yang sudah mengisi logbook di tempat mitra ini.
     */
    public function logbookList()
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        // Ambil PKL yang terkait dengan tempat mitra dan memiliki minimal 1 logbook
        $pkls = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->whereHas('logbooks')
            ->with(['mahasiswa', 'logbooks' => function ($q) {
                $q->orderBy('tgl', 'desc');
            }])
            ->get();

        return view('mitra.logbook_list', compact('pkls'));
    }

public function dashboard()
{
    $mitra = Auth::user()->mitra;

    if (!$mitra) {
        abort(403, 'Data mitra tidak ditemukan.');
    }

    $pkls = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
            $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
        })
        ->where('status', 'aktif') // tambahkan ini
        ->with('mahasiswa')
        ->get();

    $jumlahMahasiswa = $pkls->count();

    $pklIds = $pkls->pluck('id');

    // ======================
    // STATISTIK TUGAS
    // ======================

    $totalTugas = TugasMitra::whereIn('id_pkl', $pklIds)->count();

    $sudahSubmit = TugasMitraSubmit::whereIn('id_pkl', $pklIds)->count();

    $belumSubmit = $totalTugas - $sudahSubmit;

    // ======================
    // STATUS TUGAS
    // ======================

    $tugasPending = TugasMitraSubmit::whereIn('id_pkl', $pklIds)
        ->where('status', 'pending')
        ->where('revisi', false)
        ->count();

    $tugasRevisi = TugasMitraSubmit::whereIn('id_pkl', $pklIds)
        ->where('revisi', true)
        ->count();

    $tugasSelesai = TugasMitraSubmit::whereIn('id_pkl', $pklIds)
        ->where('status', 'selesai')
        ->count();

    return view('mitra.dashboard', [
        'mitra' => $mitra,
        'jumlahMahasiswa' => $jumlahMahasiswa,

        'sudahSubmit' => $sudahSubmit,
        'belumSubmit' => $belumSubmit,

        'totalTugas' => $totalTugas,
        'tugasPending' => $tugasPending,
        'tugasRevisi' => $tugasRevisi,
        'tugasSelesai' => $tugasSelesai,
    ]);
}
}