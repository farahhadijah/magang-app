<?php

namespace App\Services;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Staff;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class UserAutoCreateService
{
    public static function fromMahasiswa(Mahasiswa $mahasiswa) 
    {
        return User::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'username'     => $mahasiswa->nim,
                'password'    => Hash::make($mahasiswa->nim),
                'role'         => 'mahasiswa',
                'is_active'   => $mahasiswa->is_active ?? true,
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
                'password'     => Hash::make($dosen->nidn),
                'role'         => 'dosen',
                'is_active'    => $dosen->is_active ?? true,
                'first_login'  => true
            ]
        );
    }

    public static function fromStaff(Staff $staff)
    {
        $role = match ($staff->jabatan) {
            'Kaprodi'  => 'kaprodi',
            'Staff TU' => 'staff_tu',
            default    => throw new \LogicException(
                'Jabatan staff tidak valid untuk auto-create user'
            ),
        };

        return User::updateOrCreate(
            ['staff_id' => $staff->id],
            [
                'username'     => $staff->nip,
                'password'     => Hash::make($staff->nip),
                'role'         => $role, // staff_tu / kaprodi
                'is_active'    => $staff->is_active ?? true,
                'first_login'  => true
            ]
        );
    }
}