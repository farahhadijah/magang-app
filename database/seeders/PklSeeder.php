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
        Pkl::create([
            'id_pengajuan_pkl' => PengajuanPkl::first()->id,
            'id_dosen' => User::where('role', 'dosen')->first()->id,
            'tgl_mulai' => now(),
            'tgl_selesai' => now()->addMonths(3),
            'status' => 'aktif'
        ]);
    }
}
