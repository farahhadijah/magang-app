<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'mahasiswa')->first();

        Mahasiswa::create([
            'id_user' => $user->id,
            'nim' => '20210001',
            'prodi' => 'Informatika',
            'angkatan' => 2021,
            'no_hp' => '08123456789'
        ]);
    }
}
