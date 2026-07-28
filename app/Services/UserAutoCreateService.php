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
        $user = User::firstOrNew([
            'mahasiswa_id' => $mahasiswa->id
        ]);

        $user->username = $mahasiswa->nim;
        $user->role = 'mahasiswa';
        $user->is_active = $mahasiswa->is_active ?? true;

        if (!$user->exists) {
            $user->password = Hash::make($mahasiswa->nim);
            $user->first_login = true;
        }

        $user->save();

        return $user;
    }

    public static function fromDosen(Dosen $dosen)
    {
        $user = User::firstOrNew([
            'dosen_id' => $dosen->id
        ]);

        $user->username = $dosen->nidn;
        $user->role = 'dosen';
        $user->is_active = $dosen->is_active ?? true;

        if (!$user->exists) {
            $user->password = Hash::make($dosen->nidn);
            $user->first_login = true;
        }

        $user->save();

        return $user;
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