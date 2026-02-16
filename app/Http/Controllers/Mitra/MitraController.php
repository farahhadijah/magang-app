<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pkl;

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

    public function dashboard()
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        $pkls = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->with('mahasiswa')
            ->get();

        $jumlahMahasiswa = $pkls->count();

        return view('mitra.dashboard', [
            'mitra' => $mitra,
            'jumlahMahasiswa' => $jumlahMahasiswa,
        ]);
    }
}