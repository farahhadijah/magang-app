<?php
namespace App\Models;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Staff;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    public function getAuthIdentifierName()
    {
        return 'username';
    }
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
    public function mitra()
    {
        return $this->hasOne(Mitra::class);
    }
    public function getNama()
{
    $role = $this->role;

    // STAFF / TU / KAPRODI
    if (in_array($role, ['staf', 'staff_tu', 'kaprodi'], true)) {
        return $this->staff?->nama ?? $this->username ?? null;
    }

    // MAHASISWA
    if ($role === 'mahasiswa') {
        return $this->mahasiswa?->nama ?? $this->username ?? null;
    }

    // DOSEN
    if ($role === 'dosen') {
        return $this->dosen?->nama ?? $this->username ?? null;
    }

    // ADMIN
    if ($role === 'admin') {
        return $this->username ?? null;
    }

    // MITRA
    if ($role === 'mitra') {
        return $this->mitra?->nama ?? $this->username ?? null;
    }

    return $this->username ?? null;
}
}