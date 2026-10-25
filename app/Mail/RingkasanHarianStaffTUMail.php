<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RingkasanHarianStaffTUMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaStaff,
        public int $jumlahPengajuan,
        public int $jumlahSurat,
        public int $jumlahNilai,
        public string $tanggal,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ringkasan Tugas Harian Staff TU - SIBOLANG',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ringkasan-harian-staff-tu',
        );
    }
}
