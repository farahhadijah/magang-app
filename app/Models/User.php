<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $fillable = [
        'username',
        'password',
        'role',
        'is_active',
        'first_login',
        'mahasiswa_id',
        'dosen_id',
        'staff_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

public function mahasiswa()
{
    return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
}

public function dosen()
{
    return $this->belongsTo(Dosen::class, 'dosen_id');
}

public function staff()
{
    return $this->belongsTo(Staff::class, 'staff_id');
}
}