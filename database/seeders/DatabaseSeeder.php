<?php

namespace Database\Seeders;

use Database\Seeders\PklSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\DosenSeeder;
use Database\Seeders\StaffSeeder;
use Database\Seeders\MahasiswaSeeder;
use Database\Seeders\TempatPklSeeder;
use Database\Seeders\VerifikasiSeeder;
use Database\Seeders\PengajuanPklSeeder;
use Database\Seeders\DokumenPengajuanSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProdiSeeder::class,
            MahasiswaSeeder::class,
            TempatPklSeeder::class,
            PengajuanPklSeeder::class,
            DokumenPengajuanSeeder::class,
            VerifikasiSeeder::class,
            PklSeeder::class,
            StaffSeeder::class,
            DosenSeeder::class,
            UserSeeder::class,
        ]);
    }
}