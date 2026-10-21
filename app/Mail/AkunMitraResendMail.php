<?php

namespace App\Mail;

use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AkunMitraResendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param string $namaTempat   Nama instansi
     * @param string $username     Username akun mitra
     * @param string $password     Password plaintext (baru, hasil reset)
     * @param string $jenis        Salah satu dari EmailLog::JENIS_* 
     *                             (resend | edit_email | reset_password)
     * @param string|null $namaStaff  Nama staff TU yang men-trigger (untuk transparansi)
     */
    public function __construct(
        public string $namaTempat,
        public string $username,
        public string $password,
        public string $jenis,
        public ?string $namaStaff = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->jenis) {
            EmailLog::JENIS_RESEND         => 'Kirim Ulang: Informasi Akun Mitra SIBOLANG',
            EmailLog::JENIS_EDIT_EMAIL     => 'Pembaruan Email: Informasi Akun Mitra SIBOLANG',
            EmailLog::JENIS_RESET_PASSWORD => 'Reset Password: Informasi Akun Mitra SIBOLANG',
            default                        => 'Informasi Akun Mitra SIBOLANG',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.akun_mitra_resend',
            with: [
                'jenis'     => $this->jenis,
                'namaStaff' => $this->namaStaff,
            ],
        );
    }
}