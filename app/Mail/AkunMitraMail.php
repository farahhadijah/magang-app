<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AkunMitraMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaTempat,
        public string $username,
        public string $password
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Akun Mitra SIBOLANG'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.akun_mitra'
        );
    }
}
