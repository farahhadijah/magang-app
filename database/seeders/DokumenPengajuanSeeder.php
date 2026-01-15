<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengajuanPkl;
use App\Models\DokumenPengajuan;

class DokumenPengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $pengajuan = PengajuanPkl::first();

        // ❗ Jika belum ada pengajuan PKL, STOP
        if (!$pengajuan) {
            return;
        }

        $dokumen = ['KHS', 'Pembayaran', 'StudiTour'];

        foreach ($dokumen as $item) {
            DokumenPengajuan::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'jenis_dokumen' => $item,
                'path_file' => 'dummy/' . $item . '.pdf',
                'status_verifikasi' => 'pending',
            ]);
        }
    }
}