<?php

namespace Database\Seeders;

use App\Models\DokumenPengajuan;
use App\Models\PengajuanPkl;
use Illuminate\Database\Seeder;

class DokumenPengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $pengajuan = PengajuanPkl::first();

        $dokumen = ['KHS', 'Pembayaran', 'StudiTour'];

        foreach ($dokumen as $item) {
            DokumenPengajuan::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'jenis_dokumen' => $item,
                'path_file' => 'dummy/' . $item . '.pdf',
                'status_verifikasi' => 'pending'
            ]);
        }
    }
}
