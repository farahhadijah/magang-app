<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPkl;
use App\Models\Pkl;
use App\Models\Mitra;

class TempatPkl extends Model
{
    protected $table = 'tempat_pkl';

    protected $fillable = [
        'nama_tempat',
        'jenis_tempat',
        'no_hp',
        'lokasi_maps',
    ];

    public function pengajuans()
    {
        return $this->hasMany(PengajuanPkl::class, 'id_tempat_pkl');
    }

    public function pkls()
    {
        return $this->hasManyThrough(
            Pkl::class,
            PengajuanPkl::class,
            'id_tempat_pkl',
            'id_pengajuan_pkl',
            'id',
            'id'
        );
    }

    public function mitra()
    {
        return $this->hasOne(Mitra::class, 'tempat_pkl_id');
    }
}