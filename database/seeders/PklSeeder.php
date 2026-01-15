<?php

namespace Database\Seeders;

use App\Models\Pkl;
use App\Models\PengajuanPkl;
use App\Models\User;
use Illuminate\Database\Seeder;

class PklSeeder extends Seeder
{
    public function run(): void
    {
        $pengajuan = PengajuanPkl::first();
        $dosen     = User::where('role', 'dosen')->first();

        // ❗ Jika data utama belum ada, hentikan seeder
        if (!$pengajuan || !$dosen) {
            return;
        }

        Pkl::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'id_dosen' => $dosen->id,
            'tgl_mulai' => now(),
            'tgl_selesai' => now()->addMonths(3),
            'status' => 'aktif',
        ]);
    }
}