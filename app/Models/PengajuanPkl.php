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
}
