<?php

namespace Database\Seeders;

// use Database\Seeders\DokumenPengajuanSeeder;
// use Database\Seeders\DosenSeeder;
// use Database\Seeders\MahasiswaSeeder;
// use Database\Seeders\PengajuanPklSeeder;
// use Database\Seeders\PklSeeder;
// use Database\Seeders\StaffSeeder;
// use Database\Seeders\TempatPklSeeder;
// use Database\Seeders\VerifikasiSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // FakultasSeeder::class,
            // ProdiSeeder::class,
            // TempatPklSeeder::class,
            // PengajuanPklSeeder::class,
            // DokumenPengajuanSeeder::class,
            // VerifikasiSeeder::class,
            // MahasiswaSeeder::class,
            AdminSeeder::class,
            // PklSeeder::class,
            // StaffSeeder::class,
            // DosenSeeder::class,
            UserSeeder::class,
        ]);
    }
}