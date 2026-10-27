<?php

namespace App\Mail;

use App\Models\LaporanAkhir;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiLaporanAkhirDosen extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LaporanAkhir $laporan,
    ) {
    }

    public function build(): static
    {
        return $this->subject(
            'Laporan PKL Menunggu Review - SIBOLANG'
        )->view('emails.notifikasi_laporan_akhir_dosen');
    }
}