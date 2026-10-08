<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumePklController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $isKaprodi = strtolower(
            $user->dosen->jabatan ?? ''
        ) === 'kaprodi';

        $search = trim(
            (string) $request->input('search', '')
        );

        $query = Pkl::query()
            ->where('pkl.status', 'selesai')
            ->whereHas('pengajuanPkl.mahasiswa');
        if (!$isKaprodi) {

            $dosenId = $user->dosen->id ?? null;

            $query->where(
                'pkl.id_dosen',
                $dosenId
            );
        } else {

            $prodiId = $user->dosen->prodi_id ?? null;

            $query->whereHas(
                'pengajuanPkl.mahasiswa',
                function ($q) use ($prodiId) {
                    $q->where(
                        'mahasiswa.prodi_id',
                        $prodiId
                    );
                }
            );
        }
        if ($search !== '') {

            $query->whereHas(
                'pengajuanPkl.mahasiswa',
                function ($q) use ($search) {
                    $q->where(
                        'mahasiswa.angkatan',
                        'like',
                        '%' . $search . '%'
                    );
                }
            );
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

        return view(
            'dosen.resume.index',
            compact(
                'angkatan',
                'isKaprodi',
                'search'
            )
        );
    }
    public function mahasiswa(Request $request, $tahun)
    {
        $user = auth()->user();

        $isKaprodi = strtolower(
            $user->dosen->jabatan ?? ''
        ) === 'kaprodi';

        $search = trim(
            (string) $request->input('search', '')
        );

        $query = Pkl::with([
            'dosen',
            'pengajuanPkl.mahasiswa',
            'pengajuanPkl.tempatPkl',
            'nilaiPkl',
            'laporanAkhir',
            'logbooks',
            'penilaianMitra',
            'suratPengantar',
            'suratBalasan',
        ])
            ->where('pkl.status', 'selesai')
            ->whereHas(
                'pengajuanPkl.mahasiswa',
                function ($q) use ($tahun) {
                    $q->where(
                        'mahasiswa.angkatan',
                        $tahun
                    );
                }
            );
        if (!$isKaprodi) {

            $dosenId = $user->dosen->id ?? null;

            $query->where(
                'pkl.id_dosen',
                $dosenId
            );
        } else {

            $prodiId = $user->dosen->prodi_id ?? null;

            $query->whereHas(
                'pengajuanPkl.mahasiswa',
                function ($q) use ($prodiId) {
                    $q->where(
                        'mahasiswa.prodi_id',
                        $prodiId
                    );
                }
            );
        }
        if ($search !== '') {
            $query->whereHas(
                'pengajuanPkl.mahasiswa',
                function ($q) use ($search) {

                    $q->where(function ($inner) use ($search) {

                        $inner->where(
                            'mahasiswa.nim',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'mahasiswa.nama',
                            'like',
                            '%' . $search . '%'
                        );

                    });

                }
            );
        }

        $pkls = $query
            ->orderByDesc('pkl.tgl_selesai')
            ->paginate(10)
            ->withQueryString();

        return view(
            'dosen.resume.mahasiswa',
            compact(
                'pkls',
                'tahun',
                'isKaprodi',
                'search'
            )
        );
    }
    public function show(Pkl $pkl)
    {
        $user = auth()->user();

        $isKaprodi = strtolower(
            $user->dosen->jabatan ?? ''
        ) === 'kaprodi';

        if (!$isKaprodi) {

            $dosenId = $user->dosen->id ?? null;

            if ($pkl->id_dosen != $dosenId) {
                abort(403);
            }

        } else {

            $prodiId = $user->dosen->prodi_id ?? null;

            $pkl->loadMissing(
                'pengajuanPkl.mahasiswa'
            );

            $mahasiswaProdi =
                $pkl->pengajuanPkl->mahasiswa->prodi_id
                ?? null;

            if ($mahasiswaProdi != $prodiId) {
                abort(403);
            }
        }

        $pkl->load([
            'dosen',
            'pengajuanPkl.mahasiswa',
            'pengajuanPkl.tempatPkl',
            'nilaiPkl',
            'laporanAkhir',
            'logbooks',
            'penilaianMitra',
            'suratPengantar',
            'suratBalasan',
        ]);

        return view(
            'dosen.resume.show',
            compact(
                'pkl',
                'isKaprodi'
            )
        );
    }
    public function logbook(Pkl $pkl)
    {
        $user = auth()->user();

        $isKaprodi = strtolower(
            $user->dosen->jabatan ?? ''
        ) === 'kaprodi';

        if (!$isKaprodi) {

            $dosenId = $user->dosen->id ?? null;

            if ($pkl->id_dosen != $dosenId) {
                abort(403);
            }

        } else {

            $prodiId = $user->dosen->prodi_id ?? null;

            $pkl->loadMissing(
                'pengajuanPkl.mahasiswa'
            );

            $mahasiswaProdi =
                $pkl->pengajuanPkl->mahasiswa->prodi_id
                ?? null;

            if ($mahasiswaProdi != $prodiId) {
                abort(403);
            }
        }

        $pkl->load([
            'pengajuanPkl.mahasiswa',
            'pengajuanPkl.tempatPkl',
        ]);

        $logbooksQuery = $pkl->logbooks()
            ->where('status_approve', 'approved')
            ->orderByDesc('tgl');

        $totalApproved = (clone $logbooksQuery)->count();

        $logbooks = $logbooksQuery
            ->paginate(20)
            ->withQueryString();

        $logbookDrive = $pkl->logbooks()
            ->whereNotNull('link_dokumentasi')
            ->where('link_dokumentasi', '!=', '')
            ->orderBy('tgl', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        return view(
            'dosen.resume.logbook',
            compact(
                'pkl',
                'isKaprodi',
                'logbooks',
                'totalApproved',
                'logbookDrive'
            )
        );
    }
}