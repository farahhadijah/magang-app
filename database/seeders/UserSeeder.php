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
        User::updateOrCreate(
            ['email' => 'staff_tu@unisla.ac.id'], // key unik
            [
                'nama' => 'Staff TU',
                'password' => Hash::make('password123'),
                'role' => 'staff_tu',
            ]
        );
        // Kaprodi
        User::updateOrCreate(
            ['email' => 'kaprodi@unisla.ac.id'], // key unik
            [
                'nama' => 'Kaprodi',
                'password' => Hash::make('password123'),
                'role' => 'kaprodi',
            ]
        );
        // Dosen
        User::updateOrCreate(
            ['email' => 'dosen@unisla.ac.id'], // key unik
            [
                'nama' => 'Dosen Pembimbing',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
            ]
        );
        // Mahasiswa
        User::updateOrCreate(
            ['email' => 'mhs@unisla.ac.id'], // key unik
            [
                'nama' => 'Mahasiswa 1',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ]
        );
    }
}
