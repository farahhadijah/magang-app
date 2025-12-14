<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Staff TU
        User::create([
            'nama' => 'Staff TU',
            'email' => 'staff_tu@unisla.ac.id',
            'password' => Hash::make('stafftu#123'),
            'role' => 'staff_tu'
        ]);

        // Kaprodi
        User::create([
            'nama' => 'Kaprodi',
            'email' => 'kaprodi@unisla.ac.id',
            'password' => Hash::make('kaprodi#123'),
            'role' => 'kaprodi'
        ]);

        // Dosen
        User::create([
            'nama' => 'Dosen Pembimbing',
            'email' => 'dosen@unisla.ac.id',
            'password' => Hash::make('dosen#123'),
            'role' => 'dosen'
        ]);

        // Mahasiswa
        User::create([
            'nama' => 'Mahasiswa 1',
            'email' => 'mhs@unisla.ac.id',
            'password' => Hash::make('mhs#123'),
            'role' => 'mahasiswa'
        ]);
    }
}
