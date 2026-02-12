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
    public function getNama()
    {
        // Normalize role variants and return the most appropriate related name.
        // Some roles in the codebase use different strings (e.g. 'staff_tu'),
        // and there is no separate 'kaprodi' user relation - kaprodi is stored
        // as a Staff entry with jabatan = 'kaprodi'. Use the Staff relation
        // for staff-like roles, and sensible fallbacks.
        $role = $this->role;

        if (in_array($role, ['staff', 'staff_tu', 'kaprodi'], true)) {
            return $this->staff?->nama ?? $this->username ?? null;
        }

        if ($role === 'mahasiswa') {
            return $this->mahasiswa?->nama ?? $this->username ?? null;
        }

        if ($role === 'dosen') {
            return $this->dosen?->nama ?? $this->username ?? null;
        }

        if ($role === 'admin') {
            // There isn't a dedicated admin relation on the User model; prefer
            // an admin.name if available, otherwise fall back to username.
            return $this->admin?->nama ?? $this->username ?? null;
        }

        // Default fallback
        return $this->username ?? $this->name ?? null;
    }

}