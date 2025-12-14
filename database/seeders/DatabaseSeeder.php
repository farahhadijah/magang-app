<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\MahasiswaSeeder;
use Database\Seeders\TempatPklSeeder;
use Database\Seeders\PengajuanPklSeeder;
use Database\Seeders\DokumenPengajuanSeeder;
use Database\Seeders\VerifikasiSeeder;
use Database\Seeders\PklSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MahasiswaSeeder::class,
            TempatPklSeeder::class,
            PengajuanPklSeeder::class,
            DokumenPengajuanSeeder::class,
            VerifikasiSeeder::class,
            PklSeeder::class,
        ]);
    }
}
