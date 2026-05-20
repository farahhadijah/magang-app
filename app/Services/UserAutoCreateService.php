<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pimpinan;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAutoCreateService
{
    public static function fromMahasiswa(Mahasiswa $mahasiswa) 
    {
        return User::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'username'     => $mahasiswa->nim,
                // plain password: model mutator will hash it
                'password'    => Hash::make($mahasiswa->nim),
                'role'         => 'mahasiswa',
                'is_active'    => $mahasiswa->is_active ?? true,
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
        return User::updateOrCreate(
            ['staff_id' => $staff->id],
            [
                'username'     => $staff->nip,
                'password'     => Hash::make($staff->nip),
                'role'         => 'staff_tu',
                'is_active'    => $staff->is_active ?? true,
                'first_login'  => true
            ]
        );
    }

    public static function fromPimpinan(Pimpinan $pimpinan)
    {
        return User::updateOrCreate(
            ['pimpinan_id' => $pimpinan->id],
            [
                'username'     => $pimpinan->nip,
                'password'     => Hash::make($pimpinan->nip),
                'role'         => 'pimpinan',
                'is_active'    => $pimpinan->is_active ?? true,
                'first_login'  => true
            ]
        );
    }
}