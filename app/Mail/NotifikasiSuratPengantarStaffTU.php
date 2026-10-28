<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiSuratPengantarStaffTU extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaStaff,
        public int $jumlahBelumDicetak,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat Pengantar PKL Perlu Dikelola - SIBOLANG'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi-surat-pengantar-staff-tu'
        );
    }
}
