<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Dosen extends Model
{
    use HasFactory;
    protected $table = 'dosen';
    protected $fillable = [
        'nidn',
        'nama',
        'prodi_id',
        'keahlian',
        'jabatan',
        'no_hp',
        'is_active',
    ];
    /**
     * DEFAULT VALUE
     * mencegah error is_active NULL
     */
    protected $attributes = [
        'is_active' => true,
    ];
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
    public function mahasiswaBimbingan()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_wali_id');
    }
     public function user()
    {
        return $this->hasOne(User::class, 'dosen_id');
    }
    public function pkl()
    {
        return $this->hasMany(Pkl::class, 'id_dosen');
    }
}