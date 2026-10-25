<?php

namespace App\Console\Commands;

use App\Mail\RingkasanHarianKaprodiMail;
use App\Models\Dosen;
use App\Models\PengajuanPkl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class KirimRingkasanHarianKaprodi extends Command
{
    protected $signature = 'sibolang:ringkasan-kaprodi';

    protected $description =
        'Mengirim ringkasan pengajuan PKL yang menunggu verifikasi Kaprodi';

    public function handle(): int
    {
        $kaprodiList = Dosen::query()
            ->where('jabatan', 'Kaprodi')
            ->where('is_active', 1)
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->whereNotNull('prodi_id')
            ->get(['id', 'nama', 'email', 'prodi_id']);

        foreach ($kaprodiList as $kaprodi) {
            try {
                // Ambil pengajuan yang menunggu verifikasi
                // khusus untuk program studi Kaprodi tersebut.
                $pengajuans = PengajuanPkl::query()
                    ->with(['mahasiswa', 'tempatPkl'])
                    ->where('status', 'pending_kaprodi')
                    ->whereHas('mahasiswa', function ($query) use ($kaprodi) {
                        $query->where('prodi_id', $kaprodi->prodi_id);
                    })
                    ->orderBy('tgl_pengajuan')
                    ->get();

                $jumlahPengajuan = $pengajuans->count();

                // Tidak mengirim email jika tidak ada pengajuan.
                if ($jumlahPengajuan === 0) {
                    continue;
                }

                Mail::to($kaprodi->email)->send(
                    new RingkasanHarianKaprodiMail(
                        namaKaprodi: $kaprodi->nama,
                        jumlahPengajuan: $jumlahPengajuan,
                        pengajuans: $pengajuans,
                        tanggal: now('Asia/Jakarta')->format('d-m-Y'),
                    )
                );

                $this->info(
                    "Ringkasan berhasil dikirim kepada {$kaprodi->email}."
                );
            } catch (Throwable $e) {
                Log::error(
                    'Gagal mengirim ringkasan harian Kaprodi.',
                    [
                        'dosen_id' => $kaprodi->id,
                        'email' => $kaprodi->email,
                        'prodi_id' => $kaprodi->prodi_id,
                        'error' => $e->getMessage(),
                    ]
                );

                $this->error(
                    "Gagal mengirim email kepada {$kaprodi->email}."
                );

                // Lanjut memproses Kaprodi lainnya.
                continue;
            }
        }

        return self::SUCCESS;
    }
}
