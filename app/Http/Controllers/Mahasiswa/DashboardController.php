<?php
namespace App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanPkl;
class DashboardController extends Controller
{
        public function index()
        {
            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;
            if (!$mahasiswa) {
                abort(403, 'Mahasiswa tidak ditemukan');
            }
            // SATU pengajuan TERBARU (berdasarkan updated_at)
            $pengajuan = PengajuanPkl::with([
                    'tempatPkl',
                    'pkl.dosen',
                    'verifikasi' // ⚠️ penting untuk timeline
                ])
                ->where('id_mhs', $mahasiswa->id)
                ->latest('created_at')
                ->first();
            $status = $pengajuan?->status;
            $timeline = [
            'pengajuan'  => (bool) $pengajuan,
            'verifikasi' => in_array($status, [
                'diverifikasi_tu',
                'pending_kaprodi',
                'disetujui',
            ]),
    'selesai' => $status === 'disetujui',
];
            return view('mahasiswa.dashboard', compact('pengajuan', 'timeline'));
        }
}