<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PengajuanPkl;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pengajuanPkl()
    {
        return $this->hasMany(PengajuanPkl::class, 'id_mhs');
    }
}
?>
