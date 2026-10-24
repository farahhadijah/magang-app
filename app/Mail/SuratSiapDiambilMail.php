<?php

namespace App\Mail;

use App\Models\PengajuanPkl;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuratSiapDiambilMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PengajuanPkl $pengajuan
    ) {
        $this->pengajuan->loadMissing('mahasiswa');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat Pengantar PKL Siap Diambil',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.surat_siap_diambil',
        );
    }
}