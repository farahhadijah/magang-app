<?php
namespace App\Http\Controllers\Dosen;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logbook;
class ReviewLogbookController extends Controller
{
    /**
     * Halaman index review logbook dosen
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user || !$user->dosen) {
            abort(403, 'Akun ini bukan dosen');
        }

        $dosen = $user->dosen;

        $logbooks = Logbook::query()
            ->whereHas('pkl', function ($q) use ($dosen) {
                $q->where('id_dosen', $dosen->id)
                ->where('status', 'aktif');
            })
            ->with([
                'pkl:id,id_dosen,status,id_pengajuan_pkl',
                'pkl.pengajuanPkl.mahasiswa',
                'pkl.pengajuanPkl.mahasiswa:id,nama'
            ])
            ->orderByDesc('tgl')
            ->paginate(20); 

        return view('dosen.logbook.index', compact('logbooks'));
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
            'status'  => 'required|in:approved,revisi',
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
            'status'  => 'required|in:approved,revisi',
            'catatan' => 'nullable|required_if:status,revisi|string|max:2000',
        ]);
        return $this->processReview($request, $logbook, true);
    }
    /**
     * Core Review Logic
     */
    private function processReview(Request $request, Logbook $logbook, $isAjax = false)
    {
        $dosen = auth()->user()->dosen;
        if ($logbook->pkl->id_dosen !== $dosen->id) {
    return response()->json([
        'success' => false,
        'message' => 'Anda tidak memiliki akses ke logbook ini.'
    ], 403);
}

if ($logbook->pkl->status !== 'aktif') {
    return response()->json([
        'success' => false,
        'message' => 'PKL sudah selesai.'
    ], 403);
}

if ($logbook->status_approve === 'approved') {
    return response()->json([
        'success' => false,
        'message' => 'Logbook sudah disetujui dan tidak bisa diubah.'
    ], 403);
}
        $logbook->update([
            'status_approve' => $request->status,
            'catatan'        => $request->status === 'approved'
                                ? null
                                : $request->catatan,
        ]);
        if ($isAjax) {
            return response()->json([
                'success' => true,
                'status'  => $logbook->status_approve,
                'catatan' => $logbook->catatan,
            ]);
        }
        return redirect()
            ->route('dosen.logbook.index')
            ->with('success', 'Logbook berhasil direview.');
    }
    public function bulkApprove(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->dosen) {
            abort(403);
        }

        $request->validate([
            'logbook_ids' => 'required|array',
            'logbook_ids.*' => 'exists:logbook,id'
        ]);

        $dosenId = $user->dosen->id;

        Logbook::whereIn('id', $request->logbook_ids)
            ->where('status_approve', 'pending')
            ->whereHas('pkl', function ($q) use ($dosenId) {
                $q->where('id_dosen', $dosenId)
                ->where('status', 'aktif');
            })
            ->update([
                'status_approve' => 'approved',
                'catatan' => null
            ]);
        return redirect()
        ->back()
        ->with('success', 'Logbook terpilih berhasil disetujui.');
    }
}