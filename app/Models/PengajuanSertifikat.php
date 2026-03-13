<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSertifikat extends Model
{
    protected $table = 'pengajuan_sertifikat';

    protected $fillable = [
        'pkl_id',
        'mitra_id',
        'tanggal_pengajuan',
        'file_sertifikat',
        'status',
        'catatan'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function pkl()
    {
        return $this->belongsTo(Pkl::class);
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}