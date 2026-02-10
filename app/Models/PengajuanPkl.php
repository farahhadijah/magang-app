<?php

namespace App\Models;
use App\Models\Pkl;
use App\Models\Verifikasi;
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
    public function pkl()
{
    return $this->hasOne(Pkl::class, 'id_pengajuan_pkl');
}
public function verifikasi()
{
    return $this->hasMany(
        Verifikasi::class,
        'id_pengajuan_pkl'
    );
}

    /* ================= HELPER TU ================= */

   public function bisaDiverifikasiTu(): bool
{
    return $this->status === 'pending_tu';
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
    return $this->status === 'pending_tu'
        && $this->semuaDokumenValid();
}

public function sudahDiverifikasiTu(): bool
{
    return $this->status === 'diverifikasi_tu';
}

    public function bisaDikembalikanKeMahasiswa(): bool
{
    return $this->status === 'pending_tu'
        && $this->adaDokumenInvalid();
}
    /* ================= QUERY SCOPE ================= */

    public function scopeUntukTu($query)
{
    return $query->where('status', 'pending_tu');
}
public function statusLabel(): array
{
    return match ($this->status) {
        'draft' => [
            'text' => 'Draft',
            'class' => 'text-gray-700 bg-gray-100',
        ],
        'pending_tu' => [
            'text' => 'Menunggu Verifikasi TU',
            'class' => 'text-amber-800 bg-amber-100',
        ],
        'pending_kaprodi' => [
            'text' => 'Menunggu Verifikasi Kaprodi',
            'class' => 'text-amber-800 bg-amber-100',
        ],
        'diverifikasi_tu' => [
            'text' => 'Terverifikasi TU',
            'class' => 'text-indigo-800 bg-indigo-100',
        ],
        'ditolak_tu' => [
            'text' => 'Ditolak TU',
            'class' => 'text-red-800 bg-red-100',
        ],
        'disetujui' => [
            'text' => 'Disetujui',
            'class' => 'text-green-800 bg-green-100',
        ],
        default => [
            'text' => ucfirst($this->status),
            'class' => 'text-gray-800 bg-gray-100',
        ],
    };
}

}