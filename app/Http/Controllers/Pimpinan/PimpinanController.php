<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    public function index()
    {
        $fakultas = DB::table('fakultas')->get();

        return view('pimpinan.index', compact('fakultas'));
    }

    public function prodi(Request $request, $fakultas_id)
    {
        $fakultas = DB::table('fakultas')->get();

        // 🔥 ambil semua angkatan unik
        $angkatanList = DB::table('mahasiswa')
            ->select('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        $angkatan = $request->angkatan;

        $query = DB::table('prodi as p')
            ->join('mahasiswa as m', 'm.prodi_id', '=', 'p.id')
            ->where('p.fakultas_id', $fakultas_id);

        if ($angkatan) {
            $query->where('m.angkatan', $angkatan);
        }

        $prodi = $query->select(
            'p.id as prodi_id',
            'p.nama as nama_prodi',
            'm.angkatan',
            DB::raw('COUNT(m.id) as jumlah_mahasiswa')
        )
        ->groupBy('p.id', 'p.nama', 'm.angkatan')
        ->orderBy('p.nama')
        ->orderByDesc('m.angkatan')
        ->get();

        return view('pimpinan.prodi', compact(
            'prodi',
            'fakultas',
            'angkatanList',
            'angkatan'
        ));
    }

    public function mahasiswa(Request $request, $prodi_id, $angkatan)
{
    $fakultas = DB::table('fakultas')->get();

    $latestPengajuan = DB::table('pengajuan_pkl')
        ->select('id_mhs', DB::raw('MAX(id) as last_id'))
        ->groupBy('id_mhs');

    // 🔥 BASE QUERY (dipakai ulang)
    $base = DB::table('mahasiswa as m')
        ->leftJoinSub($latestPengajuan, 'lp', function ($join) {
            $join->on('m.id', '=', 'lp.id_mhs');
        })
        ->leftJoin('pengajuan_pkl as pp', 'pp.id', '=', 'lp.last_id')
        ->leftJoin('pkl as pk', 'pk.id_pengajuan_pkl', '=', 'pp.id')
        ->where('m.prodi_id', $prodi_id)
        ->where('m.angkatan', $angkatan)
        ->select(
            'm.id',
            'm.nim',
            'm.nama',
            'pp.status as status_pengajuan',
            'pk.status as status_pkl'
        );

    $mengajukan = (clone $base)
        ->whereIn('pp.status', ['pending_tu', 'pending_kaprodi'])
        ->paginate(5, ['*'], 'mengajukan_page');

    $sedang = (clone $base)
        ->where('pk.status', 'aktif')
        ->paginate(5, ['*'], 'sedang_page');

    $selesai = (clone $base)
        ->where('pk.status', 'selesai')
        ->paginate(5, ['*'], 'selesai_page');

    $belum = (clone $base)
        ->whereNull('pp.status')
        ->whereNull('pk.status')
        ->paginate(8, ['*'], 'belum_page');

    return view('pimpinan.mahasiswa', compact(
        'mengajukan',
        'sedang',
        'selesai',
        'belum',
        'fakultas',
        'angkatan'
    ));
}
}