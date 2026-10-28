<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiPengajuanStaffTU extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaStaff,
        public int $jumlahPengajuan,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan PKL Menunggu Verifikasi - SIBOLANG',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi-pengajuan-staff-tu',
        );
    }
}
