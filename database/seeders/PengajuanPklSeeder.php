<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;

class PengajuanPklSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = Mahasiswa::first();
        $tempat = TempatPkl::first();

        // ❗ Jika data master belum ada, hentikan
        if (!$mahasiswa || !$tempat) {
            return;
        }

        PengajuanPkl::create([
            'id_mhs' => $mahasiswa->id,
            'id_tempat_pkl' => $tempat->id,
            'status' => 'pending',
            'tgl_pengajuan' => now(),
        ]);
    }
}