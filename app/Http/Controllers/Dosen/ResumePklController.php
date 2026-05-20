<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use Illuminate\Http\Request;

class ResumePklController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $isKaprodi = strtolower($user->dosen->jabatan ?? '') === 'kaprodi';

        $search = trim((string) $request->input('search', ''));

        $query = Pkl::with([
            'dosen',
            'pengajuanPkl.mahasiswa',
            'pengajuanPkl.tempatPkl',
            'nilaiPkl',
            'laporanAkhir',
            'logbooks',
            'penilaianMitra',
        ])
        ->where('status', 'selesai');

        // ================= DOSEN =================
        if (!$isKaprodi) {

            $dosenId = $user->dosen->id ?? null;

            $query->where('id_dosen', $dosenId);
        }

        // ================= KAPRODI =================
        else {

            $prodiId = $user->dosen->prodi_id ?? null;

            $query->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }

        if ($search !== '') {
            $query->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('nim', 'like', '%' . $search . '%')
                        ->orWhere('nama', 'like', '%' . $search . '%');
                });
            });
        }

        $pkls = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dosen.resume.index', compact(
            'pkls',
            'isKaprodi',
            'search'
        ));
    }

    public function show(Pkl $pkl)
    {
        $user = auth()->user();

        $isKaprodi = strtolower($user->dosen->jabatan ?? '') === 'kaprodi';

        // ================= KEAMANAN DOSEN =================
        if (!$isKaprodi) {

            $dosenId = $user->dosen->id ?? null;

            if ($pkl->id_dosen != $dosenId) {
                abort(403);
            }
        }

        // ================= LOAD RELASI =================
        $pkl->load([
            'dosen',
            'pengajuanPkl.mahasiswa',
            'pengajuanPkl.tempatPkl',
            'nilaiPkl',
            'laporanAkhir',
            'logbooks',
            'penilaianMitra',
        ]);

        return view('dosen.resume.show', compact(
            'pkl',
            'isKaprodi'
        ));
    }

    public function logbook(Pkl $pkl)
    {
        $user = auth()->user();

        $isKaprodi = strtolower($user->dosen->jabatan ?? '') === 'kaprodi';

        // ================= VALIDASI DOSEN =================
        if (!$isKaprodi) {

            $dosenId = $user->dosen->id ?? null;

            if ($pkl->id_dosen != $dosenId) {
                abort(403);
            }
        }

        // ================= VALIDASI KAPRODI =================
        else {

            $prodiId = $user->dosen->prodi_id ?? null;

            $mahasiswaProdi =
                $pkl->pengajuanPkl
                    ->mahasiswa
                    ->prodi_id ?? null;

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
            ->orderBy('tgl', 'desc');

        $totalApproved = (clone $logbooksQuery)->count();

        $logbooks = $logbooksQuery
            ->paginate(20)
            ->withQueryString();

        return view('dosen.resume.logbook', compact(
            'pkl',
            'isKaprodi',
            'logbooks',
            'totalApproved'
        ));
    }
}