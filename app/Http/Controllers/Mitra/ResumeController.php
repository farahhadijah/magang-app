<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    public function index(Request $request)
    {
        $mitra = auth()->user()->mitra;

        abort_unless($mitra, 403, 'Data mitra tidak ditemukan.');

        $search = trim((string) $request->input('search', ''));

        $query = Pkl::query()
            ->where('pkl.status', 'selesai')
            ->whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->whereHas('pengajuanPkl.mahasiswa');
        if ($search !== '') {
            $query->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('nim', 'like', '%' . $search . '%')
                        ->orWhere('nama', 'like', '%' . $search . '%');
                });
            });
        }
        $angkatan = $query
            ->join(
                'pengajuan_pkl',
                'pkl.id_pengajuan_pkl',
                '=',
                'pengajuan_pkl.id'
            )
            ->join(
                'mahasiswa',
                'pengajuan_pkl.id_mhs',
                '=',
                'mahasiswa.id'
            )
            ->select(
                'mahasiswa.angkatan',
                DB::raw('COUNT(pkl.id) as jumlah_mahasiswa')
            )
            ->groupBy('mahasiswa.angkatan')
            ->orderByDesc('mahasiswa.angkatan')
            ->get();

        return view('mitra.resume.index', compact(
            'angkatan',
            'search'
        ));
    }
    public function mahasiswa(Request $request, $tahun)
    {
        $mitra = auth()->user()->mitra;

        abort_unless($mitra, 403, 'Data mitra tidak ditemukan.');

        $search = trim((string) $request->input('search', ''));

        $query = Pkl::with([
            'dosen',
            'pengajuanPkl.mahasiswa',
            'pengajuanPkl.tempatPkl',
        ])
            ->where('pkl.status', 'selesai')
            ->whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($tahun) {
                $q->where('angkatan', $tahun);
            });

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($search !== '') {
            $query->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('nim', 'like', '%' . $search . '%')
                        ->orWhere('nama', 'like', '%' . $search . '%');
                });
            });
        }

        $pkls = $query
            ->orderByDesc('tgl_selesai')
            ->paginate(10)
            ->withQueryString();

        return view('mitra.resume.mahasiswa', compact(
            'pkls',
            'tahun',
            'search'
        ));
    }

    public function show(Pkl $pkl)
    {
        $mitra = auth()->user()->mitra;

        abort_unless($mitra, 403, 'Data mitra tidak ditemukan.');
        $pkl->load([
            'pengajuanPkl.mahasiswa.prodi',
            'pengajuanPkl.tempatPkl',
            'dosen',
            'tugasMitra.submit',
            'logbooks',
            'penilaianMitra',
            'nilaiPkl',
            'suratPengantar',
            'suratBalasan',
        ]);

        if (
            !$pkl->pengajuanPkl ||
            $pkl->pengajuanPkl->id_tempat_pkl != $mitra->tempat_pkl_id
        ) {
            abort(403);
        }
        if ($pkl->status !== 'selesai') {
            abort(404);
        }

        return view('mitra.resume.show', compact(
            'pkl'
        ));
    }
    public function logbook(Pkl $pkl)
    {
        $mitra = auth()->user()->mitra;

        abort_unless($mitra, 403, 'Data mitra tidak ditemukan.');
        $pkl->load([
            'pengajuanPkl.mahasiswa',
            'pengajuanPkl.tempatPkl',
        ]);
        if (
            !$pkl->pengajuanPkl ||
            $pkl->pengajuanPkl->id_tempat_pkl != $mitra->tempat_pkl_id
        ) {
            abort(403);
        }
        if ($pkl->status !== 'selesai') {
            abort(404);
        }
        $logbooksQuery = $pkl->logbooks()
            ->where('status_approve', 'approved')
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc');

        $totalApproved = (clone $logbooksQuery)->count();

        $logbooks = $logbooksQuery
            ->paginate(20)
            ->withQueryString();
        $logbookDrive = $pkl->logbooks()
            ->whereNotNull('link_dokumentasi')
            ->where('link_dokumentasi', '!=', '')
            ->orderBy('tgl')
            ->orderBy('id')
            ->first();

        return view('mitra.resume.logbook', compact(
            'pkl',
            'logbooks',
            'totalApproved',
            'logbookDrive'
        ));
    }
}