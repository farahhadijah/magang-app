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
        $pengajuan = PengajuanPkl::first();

        // Verifikasi TU
        Verifikasi::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'id_user' => User::where('role', 'staff_tu')->first()->id,
            'level' => 'tu',
            'status' => 'approved',
            'catatan' => 'Lengkap',
            'tgl_verifikasi' => now()
        ]);

        // Verifikasi Kaprodi
        Verifikasi::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'id_user' => User::where('role', 'kaprodi')->first()->id,
            'level' => 'kaprodi',
            'status' => 'approved',
            'catatan' => 'Lokasi disetujui',
            'tgl_verifikasi' => now()
        ]);
    }
}
