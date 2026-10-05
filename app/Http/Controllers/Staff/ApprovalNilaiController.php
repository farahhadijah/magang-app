<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\Prodi;
use Carbon\Carbon;

class ApprovalNilaiController extends Controller
{
    /**
     * Daftar nilai PKL yang menunggu approval.
     * Hanya mahasiswa dari Prodi Staff yang sedang login.
     */
    public function index(Request $request)
    {
        $staff = auth()->user()->staff;

        if (!$staff) {
            abort(403, 'Data staff tidak ditemukan.');
        }

        if (!$staff->prodi_id) {
            abort(403, 'Staff belum memiliki Program Studi.');
        }

        /*
        |--------------------------------------------------------------------------
        | Daftar Prodi
        |--------------------------------------------------------------------------
        | Karena Staff hanya menangani Prodi miliknya sendiri,
        | maka dropdown filter hanya berisi Prodi Staff tersebut.
        */
        $prodis = Prodi::where('id', $staff->prodi_id)
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Query Nilai PKL
        |--------------------------------------------------------------------------
        */
        $query = Pkl::whereHas('nilaiPkl', function ($q) {
                $q->where('status_approval', 'pending');
            })
            ->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($staff, $request) {

                // Wajib dari Prodi Staff
                $q->where('prodi_id', $staff->prodi_id);

                /*
                |--------------------------------------------------------------------------
                | Filter Prodi
                |--------------------------------------------------------------------------
                | Tetap divalidasi agar Staff tidak dapat memasukkan
                | ID Prodi lain secara manual melalui URL.
                */
                if ($request->filled('prodi_id')) {

                    if ((int) $request->prodi_id !== (int) $staff->prodi_id) {
                        abort(
                            403,
                            'Anda tidak memiliki akses ke Program Studi tersebut.'
                        );
                    }

                    $q->where('prodi_id', $request->prodi_id);
                }
            })
            ->with([
                'pengajuanPkl.mahasiswa.prodi',
                'nilaiPkl',
                'penilaianMitra',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $pkls = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('staff.nilai.index', compact(
            'pkls',
            'prodis'
        ));
    }

    /**
     * Approve satu nilai PKL.
     */
    public function approve(Request $request, Pkl $pkl)
    {
        $staff = auth()->user()->staff;

        if (!$staff) {
            abort(403, 'Data staff tidak ditemukan.');
        }

        if (!$staff->prodi_id) {
            abort(403, 'Staff belum memiliki Program Studi.');
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan PKL milik mahasiswa dari Prodi Staff
        |--------------------------------------------------------------------------
        */
        $isAllowed = $pkl->pengajuanPkl
            && $pkl->pengajuanPkl->mahasiswa
            && $pkl->pengajuanPkl->mahasiswa->prodi_id == $staff->prodi_id;

        if (!$isAllowed) {
            abort(
                403,
                'Anda tidak memiliki akses untuk approve nilai mahasiswa dari Program Studi lain.'
            );
        }

        $nilaiPkl = $pkl->nilaiPkl;

        if (!$nilaiPkl) {
            return back()->with('error', 'Nilai tidak ditemukan.');
        }

        if ($nilaiPkl->status_approval !== 'pending') {
            return back()->with(
                'error',
                'Nilai sudah di-approve sebelumnya.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Approval Nilai
        |--------------------------------------------------------------------------
        */
        $nilaiPkl->update([
            'status_approval' => 'approved',
            'tgl_approval' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Setelah nilai di-approve, PKL menjadi selesai
        |--------------------------------------------------------------------------
        */
        $pkl->update([
            'status' => 'selesai',
            'tgl_selesai' => Carbon::now()->toDateString(),
        ]);

        return redirect()
            ->route('staff.nilai.index')
            ->with(
                'success',
                'Nilai berhasil di-approve. Status PKL mahasiswa diubah menjadi selesai.'
            );
    }

    /**
     * Bulk approve nilai PKL.
     */
    public function bulkApprove(Request $request)
    {
        $staff = auth()->user()->staff;

        if (!$staff) {
            abort(403, 'Data staff tidak ditemukan.');
        }

        if (!$staff->prodi_id) {
            abort(403, 'Staff belum memiliki Program Studi.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi ID PKL
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'pkl_ids' => ['required', 'array'],
            'pkl_ids.*' => ['integer', 'exists:pkl,id'],
        ]);

        $pklIds = $request->pkl_ids;

        /*
        |--------------------------------------------------------------------------
        | Ambil hanya PKL mahasiswa dari Prodi Staff
        |--------------------------------------------------------------------------
        */
        $pkls = Pkl::whereIn('id', $pklIds)
            ->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($staff) {
                $q->where('prodi_id', $staff->prodi_id);
            })
            ->whereHas('nilaiPkl', function ($q) {
                $q->where('status_approval', 'pending');
            })
            ->with('nilaiPkl')
            ->get();

        $successCount = 0;
        $errorCount = 0;

        /*
        |--------------------------------------------------------------------------
        | Proses Approval
        |--------------------------------------------------------------------------
        */
        foreach ($pkls as $pkl) {

            $nilaiPkl = $pkl->nilaiPkl;

            if (!$nilaiPkl || $nilaiPkl->status_approval !== 'pending') {
                $errorCount++;
                continue;
            }

            $nilaiPkl->update([
                'status_approval' => 'approved',
                'tgl_approval' => now(),
            ]);

            $pkl->update([
                'status' => 'selesai',
                'tgl_selesai' => Carbon::now()->toDateString(),
            ]);

            $successCount++;
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung data yang tidak dapat diproses
        |--------------------------------------------------------------------------
        */
        $unauthorizedCount = count($pklIds) - $pkls->count();

        $message = "Berhasil approve {$successCount} nilai.";

        if ($errorCount > 0) {
            $message .= " {$errorCount} nilai gagal atau sudah di-approve.";
        }

        if ($unauthorizedCount > 0) {
            $message .= " {$unauthorizedCount} nilai tidak dapat diproses karena bukan mahasiswa dari Program Studi Anda atau sudah tidak berstatus pending.";
        }

        return redirect()
            ->route('staff.nilai.index')
            ->with('success', $message);
    }
}