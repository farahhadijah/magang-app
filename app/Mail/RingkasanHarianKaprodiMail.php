<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class RingkasanHarianKaprodiMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaKaprodi,
        public int $jumlahPengajuan,
        public Collection $pengajuans,
        public string $tanggal,
    ) {
    }

    public function build(): static
    {
        return $this->subject(
            'Ringkasan Pengajuan PKL Menunggu Verifikasi - SIBOLANG'
        )->view('emails.ringkasan_harian_kaprodi');
    }
}
