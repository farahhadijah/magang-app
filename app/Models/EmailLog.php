<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'email_logs';

    protected $fillable = [
        'mitra_id',
        'tempat_pkl_id',
        'email_tujuan',
        'jenis',
        'status',
        'error_message',
        'triggered_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* ================= KONSTANTA JENIS ================= */

    public const JENIS_AKUN_BARU      = 'akun_baru';
    public const JENIS_RESEND         = 'resend';
    public const JENIS_EDIT_EMAIL     = 'edit_email';
    public const JENIS_RESET_PASSWORD = 'reset_password';

    /* ================= KONSTANTA STATUS ================= */

    public const STATUS_PENDING = 'pending';
    public const STATUS_SENT    = 'sent';
    public const STATUS_FAILED  = 'failed';

    /* ================= RELASI ================= */

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function tempatPkl()
    {
        return $this->belongsTo(TempatPkl::class, 'tempat_pkl_id');
    }

    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    /* ================= HELPER ================= */

    public function jenisLabel(): string
    {
        return match ($this->jenis) {
            self::JENIS_AKUN_BARU      => 'Akun Baru (Otomatis)',
            self::JENIS_RESEND         => 'Kirim Ulang',
            self::JENIS_EDIT_EMAIL     => 'Edit Email & Kirim',
            self::JENIS_RESET_PASSWORD => 'Reset Password & Kirim',
            default                    => ucfirst($this->jenis),
        };
    }

    public function statusLabel(): array
    {
        return match ($this->status) {
            self::STATUS_SENT => [
                'text'  => 'Terkirim',
                'class' => 'text-green-800 bg-green-100',
            ],
            self::STATUS_FAILED => [
                'text'  => 'Gagal',
                'class' => 'text-red-800 bg-red-100',
            ],
            self::STATUS_PENDING => [
                'text'  => 'Pending',
                'class' => 'text-amber-800 bg-amber-100',
            ],
            default => [
                'text'  => ucfirst($this->status),
                'class' => 'text-gray-800 bg-gray-100',
            ],
        };
    }
}