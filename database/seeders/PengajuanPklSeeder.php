<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\PengajuanPkl;
use App\Models\TempatPkl;
use Illuminate\Database\Seeder;

class PengajuanPklSeeder extends Seeder
{
    public function run(): void
    {
        PengajuanPkl::create([
            'id_mhs' => Mahasiswa::first()->id,
            'id_tempat_pkl' => TempatPkl::first()->id,
            'status' => 'pending',
            'tgl_pengajuan' => now()
        ]);
    }
}
