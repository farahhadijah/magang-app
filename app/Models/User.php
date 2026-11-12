<?php

namespace App\Models;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Staff;
use App\Models\Mitra;
use App\Models\Pimpinan;
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
        'staff_id',
        'pimpinan_id' 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    // ================= RELASI =================

    public function pimpinan()
    {
        return $this->belongsTo(Pimpinan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
    public function isKaprodi()
    {
        // Make check case-insensitive to avoid mismatches like 'Kaprodi' vs 'kaprodi'
        $jabatan = $this->dosen?->jabatan ?? null;
        return $jabatan ? strtolower($jabatan) === 'kaprodi' : false;
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function mitra()
    {
        return $this->hasOne(Mitra::class);
    }

        // ================= EMAIL HELPER =================
    public function profileOwner(): Mahasiswa|Dosen|Staff|null
    {
        return match ($this->role) {
            'mahasiswa' => $this->mahasiswa ?? null,
            'dosen'     => $this->dosen ?? null,
            'staff_tu'  => $this->staff ?? null,
            default     => null,
        };
    }

    /**
     * Apakah user boleh mengganti email dari halaman profile.
     */
    public function canEditEmail(): bool
    {
        return $this->profileOwner() !== null;
    }

    /**
     * Email aktif user (dari table master). Null jika tidak ada.
     */
    public function getProfileEmailAttribute(): ?string
    {
        return $this->profileOwner()?->email;
    }

    /**
     * Nama table master pemilik email (untuk rule unique kondisional).
     */
    public function profileEmailTable(): ?string
    {
        return $this->profileOwner()?->getTable();
    }

    public function getNama()
    {
        $role = $this->role;

        // STAFF TU
        if ($role === 'staff_tu') {
            return $this->staff?->nama ?? $this->username;
        }

        // MAHASISWA
        if ($role === 'mahasiswa') {
            return $this->mahasiswa?->nama ?? $this->username;
        }

        // DOSEN
        if ($role === 'dosen') {
            return $this->dosen?->nama ?? $this->username;
        }

        // MITRA
        if ($role === 'mitra') {
            return $this->mitra?->nama ?? $this->username;
        }

        // PIMPINAN 
        if ($role === 'pimpinan') {
            return $this->pimpinan?->nama ?? $this->username;
        }

        // ADMIN
        if ($role === 'admin') {
            return $this->username;
        }

        return $this->username;
    }
}