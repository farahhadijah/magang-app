<?php

namespace App\Console\Commands;

use App\Mail\RingkasanHarianStaffTUMail;
use App\Models\PengajuanPkl;
use App\Models\Pkl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class KirimRingkasanHarianStaffTU extends Command
{
    protected $signature = 'sibolang:ringkasan-staff-tu';

    protected $description = 'Mengirim ringkasan tugas tertunda harian kepada Staff TU';

    public function handle(): int
    {
        $staf = DB::table('staff')
            ->where('is_active', 1)
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->get(['id', 'nama', 'email', 'prodi_id']);

        foreach ($staf as $staff) {
            try {
                $prodiId = $staff->prodi_id;

                // 1. Pengajuan PKL menunggu verifikasi TU.
                $jumlahPengajuan = PengajuanPkl::query()
                    ->where('status', 'pending_tu')
                    ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                        $query->where('prodi_id', $prodiId);
                    })
                    ->count();

                // 2. Surat pengantar yang masih perlu diproses.
                $jumlahSurat = PengajuanPkl::query()
                    ->where('status', 'disetujui')
                    ->whereDoesntHave('pkl', function ($query) {
                        $query->where('status', 'selesai');
                    })
                    ->where(function ($query) {
                        $query->whereNull('status_surat')
                            ->orWhere('status_surat', '!=', 'siap_diambil');
                    })
                    ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                        $query->where('prodi_id', $prodiId);
                    })
                    ->count();

                // 3. Nilai PKL menunggu persetujuan Staff TU.
                $jumlahNilai = Pkl::query()
                    ->whereHas('nilaiPkl', function ($query) {
                        $query->where('status_approval', 'pending');
                    })
                    ->whereHas('pengajuanPkl.mahasiswa', function ($query) use ($prodiId) {
                        $query->where('prodi_id', $prodiId);
                    })
                    ->count();

                $totalTugas = $jumlahPengajuan
                    + $jumlahSurat
                    + $jumlahNilai;

                // Tidak mengirim email jika tidak ada tugas tertunda.
                if ($totalTugas === 0) {
                    continue;
                }

                Mail::to($staff->email)->send(
                    new RingkasanHarianStaffTUMail(
                        namaStaff: $staff->nama,
                        jumlahPengajuan: $jumlahPengajuan,
                        jumlahSurat: $jumlahSurat,
                        jumlahNilai: $jumlahNilai,
                        tanggal: now('Asia/Jakarta')->format('d-m-Y'),
                    )
                );

                $this->info(
                    "Ringkasan berhasil dikirim kepada {$staff->email}."
                );
            } catch (Throwable $e) {
                // Kegagalan email tidak mengubah data tugas.
                Log::error('Gagal mengirim ringkasan harian Staff TU.', [
                    'staff_id' => $staff->id,
                    'email' => $staff->email,
                    'error' => $e->getMessage(),
                ]);

                $this->error(
                    "Gagal mengirim email kepada {$staff->email}."
                );

                // Lanjut memproses Staff TU lainnya.
                continue;
            }
        }

        return self::SUCCESS;
    }
}
