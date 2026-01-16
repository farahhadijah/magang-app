<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PengajuanPkl;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends Model
{
     protected $attributes = [
        'is_active' => true,
    ];
    use HasFactory;
    protected $table = 'mahasiswa';
    protected $fillable = [
        'nim', 'nama', 'prodi_id', 'angkatan', 'no_hp', 'is_active'
    ];

    public function prodi() {
        return $this->belongsTo(Prodi::class);
    }

    public function user() {
        return $this->hasOne(User::class, 'mahasiswa_id');
    }

    public function pengajuanPkl()
    {
        return $this->hasMany(PengajuanPkl::class, 'id_mhs');
    }
}