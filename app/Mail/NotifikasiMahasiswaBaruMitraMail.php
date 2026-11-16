<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiMahasiswaBaruMitraMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaTempat,
        public string $namaMahasiswa,
        public string $nimMahasiswa,
        public string $prodiMahasiswa,
        public string $namaDosen,
        public ?string $tglMulai = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Mahasiswa PKL Baru - SIBOLANG'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi_mahasiswa_baru_mitra'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

