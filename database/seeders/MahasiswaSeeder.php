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

        Mahasiswa::updateOrCreate(
            ['nim' => '112310090'], // kolom UNIQUE
            [
                'id_user' => 4,
                'prodi' => 'Teknik Informatika',
                'angkatan' => 2023,
                'no_hp' => '08123456789',
            ]
        );
    }
}
