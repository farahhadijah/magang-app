<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =====================
        // STAFF TU
        // =====================
        User::updateOrCreate(
            ['username' => '19800101'], // NIP
            [
                'password' => Hash::make('19800101'),
                'role' => 'staff_tu',
                'first_login' => true,
                'is_active' => true,
            ]
        );

        // =====================
        // KAPRODI
        // =====================
        User::updateOrCreate(
            ['username' => '19750505'], // NIP
            [
                'password' => Hash::make('19750505'),
                'role' => 'kaprodi',
                'first_login' => true,
                'is_active' => true,
            ]
        );

        // =====================
        // DOSEN
        // =====================
        User::updateOrCreate(
            ['username' => '12001234'], // NIDN
            [
                'password' => Hash::make('12001234'),
                'role' => 'dosen',
                'first_login' => true,
                'is_active' => true,
            ]
        );

        // =====================
        // MAHASISWA
        // =====================
        User::updateOrCreate(
            ['username' => '210441100001'], // NIM
            [
                'password' => Hash::make('210441100001'),
                'role' => 'mahasiswa',
                'first_login' => true,
                'is_active' => true,
            ]
        );
    }
}