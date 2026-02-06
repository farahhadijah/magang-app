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

                // status sudah diproses staff
                'verifikasi' => in_array($status, ['disetujui', 'ditolak']),

                // PKL sudah dibuat
                'berjalan'   => in_array($status, ['berjalan', 'selesai']),

                // PKL selesai
                'selesai'    => $status === 'selesai',
            ];


            return view('mahasiswa.dashboard', compact('pengajuan', 'timeline'));
        }


}