<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RingkasanHarianKaprodiMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaKaprodi,
        public int $jumlahPengajuan,
        public string $urlSibolang,
    ) {
    }

    public function build(): static
    {
        return $this->subject(
            'Pengajuan PKL Menunggu Verifikasi - SIBOLANG'
        )->view('emails.ringkasan_harian_kaprodi');
    }
}