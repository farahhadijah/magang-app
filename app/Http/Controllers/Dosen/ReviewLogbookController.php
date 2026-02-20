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
        $logbooks = Logbook::whereHas('pkl', function ($q) use ($dosen) {
                $q->where('id_dosen', $dosen->id)
                  ->where('status', 'aktif');
            })
            ->with(['pkl.pengajuanPkl.mahasiswa'])
            ->orderByDesc('tgl')
            ->get();
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
            'catatan' => 'required_if:status,revisi|string|max:2000',
        ]);
        return $this->processReview($request, $logbook, true);
    }
    /**
     * Core Review Logic
     */
    private function processReview(Request $request, Logbook $logbook, $isAjax = false)
    {
        $dosen = auth()->user()->dosen;
        // 🔐 Pastikan logbook milik dosen pembimbing
        if ($logbook->pkl->id_dosen !== $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke logbook ini.');
        }
        // 🔒 PKL harus masih aktif
        if ($logbook->pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }
        // 🔒 Tidak boleh review ulang jika sudah approved
        if ($logbook->status_approve === 'approved') {
            abort(403, 'Logbook sudah disetujui dan tidak bisa diubah.');
        }
        // 🔄 Update data
        $logbook->update([
            'status_approve' => $request->status === 'approved' ? 'approved' : 'pending',
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
}