<?php

namespace App\Services;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Staff;

class UserAutoCreateService
{
    public static function fromMahasiswa(Mahasiswa $mahasiswa)
    {
        return User::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'username'     => $mahasiswa->nim,
                'password'     => $mahasiswa->nim,
                'role'         => 'mahasiswa',
                'is_active' => $mahasiswa->is_active === null ? true : $mahasiswa->is_active,
                'first_login'  => true
            ]
        );
    }

    public static function fromDosen(Dosen $dosen)
    {
        return User::updateOrCreate(
            ['dosen_id' => $dosen->id],
            [
                'username'     => $dosen->nidn,
                'password'     => $dosen->nidn,
                'role'         => 'dosen',
                'is_active'    => $dosen->is_active ?? true,
                'first_login'  => true
            ]
        );
    }

    public static function fromStaff(Staff $staff, string $role)
    {
        return User::updateOrCreate(
            ['staff_id' => $staff->id],
            [
                'username'     => $staff->nip,
                'password'     => $staff->nip,
                'role'         => $role, // staff_tu / kaprodi
                'is_active'    => $staff->is_active ?? true,
                'first_login'  => true
            ]
        );
    }
}