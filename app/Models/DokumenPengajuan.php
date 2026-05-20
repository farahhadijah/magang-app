<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPengajuan extends Model
{
    protected $table = 'dokumen_pengajuan';

    protected $fillable = [
        'id_pengajuan_pkl',
        'jenis_dokumen',
        'path_file',
        'status_verifikasi',
        'catatan',
    ];

    /* ================= KONSTANTA JENIS ================= */

    public const JENIS_PEMBAYARAN = 'Pembayaran';
    public const JENIS_STUDI_TOUR = 'StudiTour';
    public const JENIS_KRS = 'KRS';

    /* ================= RELATION ================= */

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }

    /* ================= HELPER JENIS ================= */

    public function isKrs(): bool
    {
        return $this->jenis_dokumen === self::JENIS_KRS;
    }
    public function isPembayaran(): bool
    {
        return $this->jenis_dokumen === self::JENIS_PEMBAYARAN;
    }

    public function isStudiTour(): bool
    {
        return $this->jenis_dokumen === self::JENIS_STUDI_TOUR;
    }

    /* ================= HELPER STATUS ================= */

    public function isPending(): bool
    {
        return $this->status_verifikasi === 'pending';
    }

    public function isValid(): bool
    {
        return $this->status_verifikasi === 'valid';
    }

    public function isInvalid(): bool
    {
        return $this->status_verifikasi === 'invalid';
    }

    public function tandaiValid()
    {
        $this->update([
            'status_verifikasi' => 'valid',
            'catatan' => null,
        ]);
    }

    public function tandaiInvalid(string $catatan)
    {
        $this->update([
            'status_verifikasi' => 'invalid',
            'catatan' => $catatan,
        ]);
    }

    /* ================= SCOPE ================= */

    public function scopeKrs($query)
    {
        return $query->where('jenis_dokumen', self::JENIS_KRS);
    }

    /* ================= BADGE ================= */

    public function statusBadge(): array
    {
        return match ($this->status_verifikasi) {
            'pending' => [
                'text'  => 'Pending',
                'class' => 'text-amber-800 bg-amber-100',
            ],
            'valid' => [
                'text'  => 'Valid',
                'class' => 'text-green-800 bg-green-100',
            ],
            'invalid' => [
                'text'  => 'Invalid',
                'class' => 'text-red-800 bg-red-100',
            ],
            default => [
                'text'  => ucfirst($this->status_verifikasi),
                'class' => 'text-gray-800 bg-gray-100',
            ],
        };
    }
}