<?php

namespace Database\Seeders;

use App\Models\Verifikasi;
use App\Models\PengajuanPkl;
use App\Models\User;
use Illuminate\Database\Seeder;

class VerifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $pengajuan = PengajuanPkl::first(); $staffTu   = User::where('role', 'staff_tu')->first();
        $kaprodi   = User::where('role', 'kaprodi')->first();

        if (!$pengajuan || !$staffTu) {
            return;
        }

        // VERIFIKASI TU
        Verifikasi::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'id_user' => $staffTu->id,
            'level' => 'tu',
            'status' => 'approved',
            'catatan' => 'Lengkap',
            'tgl_verifikasi' => now(),
        ]);

        // VERIFIKASI KAPRODI (OPSIONAL)
        if ($kaprodi) {
            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user' => $kaprodi->id,
                'level' => 'kaprodi',
                'status' => 'approved',
                'catatan' => 'Lokasi disetujui',
                'tgl_verifikasi' => now(),
            ]);
        }
    }
}