<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPkl;
use App\Models\User;

class Verifikasi extends Model
{
    protected $table = 'verifikasi';

    protected $fillable = [
        'id_pengajuan_pkl',
        'id_user',
        'level',
        'status',
        'catatan',
        'tgl_verifikasi',
    ];

    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

?>