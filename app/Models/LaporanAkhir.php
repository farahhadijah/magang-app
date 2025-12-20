<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;

class LaporanAkhir extends Model
{
    public $timestamps = true;
    protected $table = 'laporan_akhir';

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
}
?>
