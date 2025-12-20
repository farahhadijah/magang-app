<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPkl;
use App\Models\User;
use App\Models\Logbook;
use App\Models\SuratPengantar;
use App\Models\LaporanAkhir;
use App\Models\NilaiPkl;

class Pkl extends Model
{
    public $timestamps = true;
    protected $table = 'pkl';

    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function logbook()
    {
        return $this->hasMany(Logbook::class, 'id_pkl');
    }

    public function suratPengantar()
    {
        return $this->hasOne(SuratPengantar::class, 'id_pkl');
    }

    public function laporanAkhir()
    {
        return $this->hasOne(LaporanAkhir::class, 'id_pkl');
    }

    public function nilaiPkl()
    {
        return $this->hasOne(NilaiPkl::class, 'id_pkl');
    }
}
?>
