<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // STAFF TU
        $staff = Staff::where('nip', '19800101')->first();
        if ($staff) {
            User::updateOrCreate(
                ['username' => $staff->nip],
                [
                    'password' => Hash::make($staff->nip),
                    'role' => 'staff_tu',
                    'first_login' => true,
                    'is_active' => true,
                    'staff_id' => $staff->id
                ]
            );
        }
        // KAPRODI
        $kaprodi = Staff::where('nip', '19750505')->first();
        if ($kaprodi) {
            User::updateOrCreate(
                ['username' => $kaprodi->nip],
                [
                    'password' => Hash::make($kaprodi->nip),
                    'role' => 'kaprodi',
                    'first_login' => true,
                    'is_active' => true,
                    'staff_id' => $kaprodi->id
                ]
            );
        }
        // DOSEN
        $dosen = Dosen::where('nidn', '12001234')->first();
        if ($dosen) {
            User::updateOrCreate(
                ['username' => $dosen->nidn],
                [
                    'password' => Hash::make($dosen->nidn),
                    'role' => 'dosen',
                    'first_login' => true,
                    'is_active' => true,
                    'dosen_id' => $dosen->id
                ]
            );
        }
        // MAHASISWA
        $mhs = Mahasiswa::where('nim', '210441100001')->first();
        if ($mhs) {

User::updateOrCreate(
    ['username' => $mhs->nim],
    [
        'password' => Hash::make($mhs->nim),
        'role' => 'mahasiswa',
        'first_login' => true,
        'is_active' => true,
        'mahasiswa_id' => $mhs->id
    ]
);

        }
    }
}