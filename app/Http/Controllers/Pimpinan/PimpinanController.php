<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    /**
     * Dashboard pimpinan
     *
     * Pimpinan hanya dapat mengakses fakultas yang menjadi tanggung jawabnya.
     */
    public function index()
    {
        $pimpinan = auth()->user()->pimpinan;

        if (!$pimpinan) {
            abort(403, 'Data pimpinan tidak ditemukan.');
        }

        $fakultas_id = $pimpinan->fakultas_id;

        $fakultas = DB::table('fakultas')
            ->where('id', $fakultas_id)
            ->first();

        if (!$fakultas) {
            abort(403, 'Fakultas pimpinan tidak ditemukan.');
        }

        return view('pimpinan.index', compact('fakultas'));
    }

    /**
     * Menampilkan daftar prodi berdasarkan fakultas pimpinan.
     */
    public function prodi(Request $request, $fakultas_id)
    {
        $pimpinan = auth()->user()->pimpinan;

        if (!$pimpinan) {
            abort(403, 'Data pimpinan tidak ditemukan.');
        }

        // Pimpinan hanya boleh mengakses fakultasnya sendiri
        if ((int) $pimpinan->fakultas_id !== (int) $fakultas_id) {
            abort(403, 'Anda tidak memiliki akses ke fakultas ini.');
        }

        $fakultas = DB::table('fakultas')
            ->where('id', $pimpinan->fakultas_id)
            ->first();

        $angkatanList = DB::table('mahasiswa')
            ->whereIn(
                'prodi_id',
                DB::table('prodi')
                    ->where('fakultas_id', $pimpinan->fakultas_id)
                    ->pluck('id')
            )
            ->select('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        $angkatan = $request->angkatan;

        $query = DB::table('prodi as p')
            ->join('mahasiswa as m', 'm.prodi_id', '=', 'p.id')
            ->where('p.fakultas_id', $pimpinan->fakultas_id);

        if ($angkatan) {
            $query->where('m.angkatan', $angkatan);
        }

        $prodi = $query
            ->select(
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

    /**
     * Menampilkan mahasiswa berdasarkan prodi dan angkatan.
     */
    public function mahasiswa(Request $request, $prodi_id, $angkatan)
    {
        $pimpinan = auth()->user()->pimpinan;

        if (!$pimpinan) {
            abort(403, 'Data pimpinan tidak ditemukan.');
        }

        /*
         * Pastikan prodi yang diakses memang milik
         * fakultas pimpinan yang sedang login.
         */
        $prodi = DB::table('prodi')
            ->where('id', $prodi_id)
            ->where('fakultas_id', $pimpinan->fakultas_id)
            ->first();

        if (!$prodi) {
            abort(403, 'Anda tidak memiliki akses ke program studi ini.');
        }

        $fakultas = DB::table('fakultas')
            ->where('id', $pimpinan->fakultas_id)
            ->first();

        $latestPengajuan = DB::table('pengajuan_pkl')
            ->select(
                'id_mhs',
                DB::raw('MAX(id) as last_id')
            )
            ->groupBy('id_mhs');

        /*
         * BASE QUERY
         */
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

        /*
         * MAHASISWA SEDANG MENGAJUKAN
         */
        $mengajukan = (clone $base)
            ->whereIn('pp.status', [
                'pending_tu',
                'pending_kaprodi'
            ])
            ->paginate(5, ['*'], 'mengajukan_page');

        /*
         * MAHASISWA SEDANG PKL
         */
        $sedang = (clone $base)
            ->where('pk.status', 'aktif')
            ->paginate(5, ['*'], 'sedang_page');

        /*
         * MAHASISWA SUDAH SELESAI PKL
         */
        $selesai = (clone $base)
            ->where('pk.status', 'selesai')
            ->paginate(5, ['*'], 'selesai_page');

        /*
         * MAHASISWA BELUM PKL
         */
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
            'prodi',
            'angkatan'
        ));
    }
}