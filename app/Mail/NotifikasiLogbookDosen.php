<?php

namespace App\Mail;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiLogbookDosen extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Logbook $logbook,
    ) {
    }

    public function build(): static
    {
        return $this->subject(
            'Logbook Mahasiswa Menunggu Verifikasi - SIBOLANG'
        )->view('emails.notifikasi_logbook_dosen');
    }
}