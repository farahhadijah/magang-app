<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPkl extends Model
{
    protected $table = 'pengajuan_pkl';

    protected $fillable = [
        'id_mhs',
        'id_tempat_pkl',
        'status',
        'tgl_pengajuan',
        'catatan_tu',
        'catatan_kaprodi',
    ];

    /* ================= RELATION ================= */

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs');
    }

    public function tempatPkl()
    {
        return $this->belongsTo(TempatPkl::class, 'id_tempat_pkl');
    }

    public function dokumenPengajuan()
    {
        return $this->hasMany(DokumenPengajuan::class, 'id_pengajuan_pkl');
    }

    /* ================= HELPER TU ================= */

    public function bisaDiverifikasiTu(): bool
    {
        return $this->status === 'pending';
    }

    public function adaDokumenInvalid(): bool
{
    return $this->dokumenPengajuan()
        ->where('status_verifikasi', 'invalid')
        ->exists();
}

    public function semuaDokumenValid(): bool
{
    return $this->dokumenPengajuan()
        ->where('status_verifikasi', '!=', 'valid')
        ->count() === 0;
}

    public function bisaDisetujuiTu(): bool
{
    return $this->status === 'pending'
        && $this->semuaDokumenValid();
}

    public function bisaDikembalikanKeMahasiswa(): bool
{
    return $this->status === 'pending'
        && $this->adaDokumenInvalid();
}

    /* ================= QUERY SCOPE ================= */

    public function scopeUntukTu($query)
{
    return $query->where('status', 'pending');
}
}