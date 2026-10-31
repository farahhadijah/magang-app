<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiDosenPembimbing extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaDosen,
        public string $namaMahasiswa,
        public string $nimMahasiswa,
        public string $namaTempat,
        public ?string $prodiMahasiswa = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mahasiswa Bimbingan PKL Baru',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi-dosen-pembimbing',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}