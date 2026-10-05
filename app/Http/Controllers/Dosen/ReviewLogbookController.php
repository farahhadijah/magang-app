<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\Logbook;

class ReviewLogbookController extends Controller
{
    /**
     * Halaman index review logbook dosen.
     *
     * Menampilkan daftar mahasiswa bimbingan
     * yang sudah memiliki minimal 1 logbook.
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user || !$user->dosen) {
            abort(403, 'Akun ini bukan dosen');
        }

        $dosen = $user->dosen;

        /*
        |--------------------------------------------------------------------------
        | Ambil PKL aktif mahasiswa bimbingan
        |--------------------------------------------------------------------------
        |
        | Hanya mahasiswa yang sudah memiliki logbook
        | yang ditampilkan pada halaman index.
        |
        */
        $pkls = Pkl::query()
            ->where('id_dosen', $dosen->id)
            ->where('status', 'aktif')
            ->whereHas('pengajuanPkl.mahasiswa')
            ->whereHas('logbooks')
            ->with([
                'pengajuanPkl.mahasiswa:id,nim,nama',

                /*
                | Ambil logbook terbaru untuk informasi ringkas
                | di halaman index.
                */
                'logbooks' => function ($query) {
                    $query
                        ->latest('tgl')
                        ->latest('id');
                },
            ])
            ->withCount('logbooks')
            ->orderByDesc('id')
            ->paginate(10);

        return view('dosen.logbook.index', compact('pkls'));
    }


    /**
     * Halaman detail logbook satu mahasiswa.
     *
     * URL menggunakan ID PKL karena satu PKL
     * merepresentasikan satu mahasiswa pada satu periode PKL.
     */
    public function detail(Pkl $pkl)
    {
        $user = auth()->user();

        if (!$user || !$user->dosen) {
            abort(403, 'Akun ini bukan dosen');
        }

        $dosen = $user->dosen;

        /*
        |--------------------------------------------------------------------------
        | Pastikan PKL benar-benar milik dosen login
        |--------------------------------------------------------------------------
        */
        if ($pkl->id_dosen !== $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | PKL harus masih aktif
        |--------------------------------------------------------------------------
        */
        if ($pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil mahasiswa
        |--------------------------------------------------------------------------
        */
        $pkl->load([
            'pengajuanPkl.mahasiswa:id,nim,nama',
            'pengajuanPkl.tempatPkl',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ambil seluruh logbook mahasiswa
        |--------------------------------------------------------------------------
        */
        $logbooks = $pkl->logbooks()
            ->orderByDesc('tgl')
            ->orderByDesc('id')
            ->paginate(20);

        /*
        |--------------------------------------------------------------------------
        | Jumlah logbook yang masih pending
        |--------------------------------------------------------------------------
        */
        $pendingLogbooks = $pkl->logbooks()
            ->where('status_approve', 'pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Ambil link Google Drive
        |--------------------------------------------------------------------------
        |
        | Konsep:
        | - Link GDrive hanya dibuat pada logbook pertama.
        | - Link tersebut tetap tersimpan pada tabel logbook.
        | - Pada halaman detail, link ditampilkan di luar tabel.
        |
        | Kita ambil logbook paling awal yang memiliki link.
        |
        */
        $logbookDrive = $pkl->logbooks()
            ->whereNotNull('link_dokumentasi')
            ->where('link_dokumentasi', '!=', '')
            ->orderBy('tgl', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        return view('dosen.logbook.detail', compact(
            'pkl',
            'logbooks',
            'logbookDrive',
            'pendingLogbooks'
        ));
    }


    /**
     * Review logbook (NON AJAX)
     */
    public function review(Request $request, Logbook $logbook)
    {
        $user = auth()->user();

        if (!$user || !$user->dosen) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,revisi',
            'catatan' => 'required_if:status,revisi|string|max:2000',
        ]);

        return $this->processReview($request, $logbook, false);
    }


    /**
     * Review logbook (AJAX)
     */
    public function reviewAjax(Request $request, Logbook $logbook)
    {
        $user = auth()->user();

        if (!$user || !$user->dosen) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,revisi',
            'catatan' => 'nullable|required_if:status,revisi|string|max:2000',
        ]);

        return $this->processReview($request, $logbook, true);
    }


    /**
     * Core Review Logic
     */
    private function processReview(
        Request $request,
        Logbook $logbook,
        bool $isAjax = false
    ) {
        $dosen = auth()->user()->dosen;

        /*
        |--------------------------------------------------------------------------
        | Pastikan logbook merupakan milik mahasiswa bimbingan dosen
        |--------------------------------------------------------------------------
        */
        if (
            !$logbook->pkl ||
            $logbook->pkl->id_dosen !== $dosen->id
        ) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke logbook ini.',
                ], 403);
            }

            abort(403, 'Anda tidak memiliki akses ke logbook ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | PKL harus masih aktif
        |--------------------------------------------------------------------------
        */
        if ($logbook->pkl->status !== 'aktif') {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'PKL sudah selesai.',
                ], 403);
            }

            abort(403, 'PKL sudah selesai.');
        }

        /*
        |--------------------------------------------------------------------------
        | Logbook yang sudah disetujui tidak dapat diubah
        |--------------------------------------------------------------------------
        */
        if ($logbook->status_approve === 'approved') {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logbook sudah disetujui dan tidak bisa diubah.',
                ], 403);
            }

            abort(403, 'Logbook sudah disetujui dan tidak bisa diubah.');
        }

        $logbook->update([
            'status_approve' => $request->status,
            'catatan' => $request->status === 'approved'
                ? null
                : $request->catatan,
        ]);

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'status' => $logbook->status_approve,
                'catatan' => $logbook->catatan,
            ]);
        }

        return redirect()
            ->route('dosen.logbook.detail', $logbook->pkl->id)
            ->with('success', 'Logbook berhasil direview.');
    }


    /**
     * Bulk approve logbook.
     */
    public function bulkApprove(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->dosen) {
            abort(403);
        }

        $request->validate([
            'logbook_ids' => 'required|array',
            'logbook_ids.*' => 'exists:logbook,id',
        ]);

        $dosenId = $user->dosen->id;

        /*
        |--------------------------------------------------------------------------
        | Ambil PKL dari logbook yang dipilih
        |--------------------------------------------------------------------------
        */
        $firstLogbook = Logbook::with('pkl')
            ->whereIn('id', $request->logbook_ids)
            ->first();

        if (!$firstLogbook || !$firstLogbook->pkl) {
            abort(403, 'Logbook tidak valid.');
        }

        $pkl = $firstLogbook->pkl;

        if ($pkl->id_dosen !== $dosenId) {
            abort(403, 'Anda tidak memiliki akses ke logbook ini.');
        }

        if ($pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }

        /*
        |--------------------------------------------------------------------------
        | Approve logbook yang dipilih
        |--------------------------------------------------------------------------
        */
        Logbook::whereIn('id', $request->logbook_ids)
            ->where('status_approve', 'pending')
            ->whereHas('pkl', function ($q) use ($dosenId, $pkl) {
                $q->where('id_dosen', $dosenId)
                    ->where('id', $pkl->id)
                    ->where('status', 'aktif');
            })
            ->update([
                'status_approve' => 'approved',
                'catatan' => null,
            ]);

        return redirect()
            ->route('dosen.logbook.detail', $pkl->id)
            ->with('success', 'Logbook terpilih berhasil disetujui.');
    }
}