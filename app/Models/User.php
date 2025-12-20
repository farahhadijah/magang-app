<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\Verifikasi;
use App\Models\Pkl;

class User extends Model
{
    public $timestamps = true;
    protected $table = 'users';

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'id_user');
    }

    public function pklDosen()
    {
        return $this->hasMany(Pkl::class, 'id_dosen');
    }
}

?>