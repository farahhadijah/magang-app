<?php

namespace App\Models;

use App\Models\PengajuanPkl;
use Illuminate\Database\Eloquent\Model;

class DokumenPengajuan extends Model
{
    public $timestamps = true;

    protected $table = 'dokumen_pengajuan';

    protected $fillable = [
        'id_pengajuan_pkl',
        'jenis_dokumen',
        'path_file',
        'status_verifikasi',
        'catatan',
    ];

    /**
     * ===============================
     * RELATION
     * ===============================
     */
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }

    /**
     * ===============================
     * HELPER STATUS
     * ===============================
     */
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

    /**
     * ===============================
     * ACTION
     * ===============================
     */
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

    /**
     * ===============================
     * UI HELPER
     * ===============================
     */
    public function statusBadge(): array
    {
        if ($this->isPending()) {
            return [
                'text'  => 'Pending',
                'class' => 'text-amber-800 bg-amber-100',
            ];
        }

        if ($this->isValid()) {
            return [
                'text'  => 'Valid',
                'class' => 'text-green-800 bg-green-100',
            ];
        }

        if ($this->isInvalid()) {
            return [
                'text'  => 'Invalid',
                'class' => 'text-red-800 bg-red-100',
            ];
        }

        return [
            'text'  => ucfirst($this->status_verifikasi),
            'class' => 'text-gray-800 bg-gray-100',
        ];
    }
}