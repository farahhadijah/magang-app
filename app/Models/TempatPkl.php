<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPkl;

class TempatPkl extends Model
{
    public $timestamps = true;
    protected $table = 'tempat_pkl';
    public function pengajuanPkl()
    {
        return $this->hasMany(PengajuanPkl::class, 'id_tempat_pkl');
    }
}
