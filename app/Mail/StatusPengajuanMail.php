<?php

namespace App\Mail;

use App\Models\PengajuanPkl;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusPengajuanMail extends Mailable
{
    use Queueable, SerializesModels;

    public PengajuanPkl $pengajuan;

    public function __construct(PengajuanPkl $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function build(): static
    {
        $this->pengajuan->loadMissing([
            'mahasiswa',
            'tempatPkl',
            'dokumenPengajuan',   // pastikan relasi ini ada di model PengajuanPkl
        ]);

        $subject = match ($this->pengajuan->status) {
            'ditolak_tu'      => 'Dokumen Pengajuan PKL Perlu Diperbaiki - SIBOLANG',
            'ditolak_kaprodi' => 'Pengajuan PKL Ditolak Kaprodi - SIBOLANG',
            'disetujui'       => 'Pengajuan Disetujui, PKL Anda Aktif - SIBOLANG',
            default           => 'Informasi Pengajuan PKL - SIBOLANG',
        };

        return $this->subject($subject)
            ->view('emails.status_pengajuan');
    }
}
