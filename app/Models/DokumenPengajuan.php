<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPkl;

class DokumenPengajuan extends Model
{
    public $timestamps = true;
    protected $table = 'dokumen_pengajuan';

    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }
}
?>