<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // =====================
    // RELATION
    // =====================
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

    // =====================
    // ROLE HELPER
    // =====================
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isStaffTu()
    {
        return $this->role === 'staff_tu';
    }

    public function isKaprodi()
    {
        return $this->role === 'kaprodi';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }
}