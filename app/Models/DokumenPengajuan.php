<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPkl;

class DokumenPengajuan extends Model
{
    public $timestamps = true;
    protected $table = 'dokumen_pengajuan';
    protected $fillable = [
    'id_pengajuan_pkl',
    'path_file',      // sesuai nama kolom di database
    'jenis_dokumen',  // sesuai enum di tabel
    'catatan', 
];

    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }
}
?>