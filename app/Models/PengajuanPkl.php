<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TempatPkl;
use App\Models\Mahasiswa;
use App\Models\DokumenPengajuan;
use App\Models\Verifikasi;
use App\Models\Pkl;

class PengajuanPkl extends Model
{
    public $timestamps = true;
    protected $table = 'pengajuan_pkl';
    protected $fillable = [
    'id_mhs',
    'id_tempat_pkl',
    'status',
    'tgl_pengajuan'
];

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

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'id_pengajuan_pkl');
    }

    public function pkl()
    {
        return $this->hasOne(Pkl::class, 'id_pengajuan_pkl');
    }
    public function verifikasiTu()
    {
        return $this->hasOne(Verifikasi::class, 'id_pengajuan_pkl')
            ->where('level', 'tu');
    }
    public function verifikasiKaprodi()
    {
        return $this->hasOne(Verifikasi::class, 'id_pengajuan_pkl')
            ->where('level', 'kaprodi');
    }


    public function sudahDisetujuiTu(): bool
    {
        return $this->verifikasi()
            ->where('level', 'tu')
            ->where('status', 'approved')
            ->exists();
    }
    public function sudahDisetujuiKaprodi(): bool
    {
        return $this->verifikasi()
            ->where('level', 'kaprodi')
            ->where('status', 'approved')
            ->exists();
    }
    public function statusLabel(): array
    {
        if ($this->status === 'pending') {
            if ($this->sudahDisetujuiTu()) {
                return [
                    'text'  => 'Pending Kaprodi',
                    'class' => 'text-blue-800 bg-blue-100',
                ];
            }

            return [
                'text'  => 'Pending Verifikasi TU',
                'class' => 'text-amber-800 bg-amber-100',
            ];
        }

        if ($this->status === 'ditolak_tu') {
            return [
                'text'  => 'Ditolak TU',
                'class' => 'text-red-800 bg-red-100',
            ];
        }

        if ($this->status === 'disetujui') {
            return [
                'text'  => 'Disetujui',
                'class' => 'text-green-800 bg-green-100',
            ];
        }

        return [
            'text'  => ucfirst($this->status),
            'class' => 'text-gray-800 bg-gray-100',
        ];
    }
    public function bisaDiverifikasiTu(): bool
    {
        return $this->status === 'pending'
            && !$this->sudahDisetujuiTu()
            && !$this->verifikasi()
                ->where('level', 'tu')
                ->where('status', 'rejected')
                ->exists();
    }




}