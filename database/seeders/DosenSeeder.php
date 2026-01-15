<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        Dosen::updateOrCreate(
            ['nidn' => '12001234'],
            [
                'nama' => 'Dr. Budi Santoso',
                'no_hp' => '081234567890',
                'is_active' => true,
            ]
        );

        Dosen::updateOrCreate(
            ['nidn' => '12005678'],
            [
                'nama' => 'Dra. Siti Nurhaliza',
                'no_hp' => '081298761234',
                'is_active' => true,
            ]
        );
    }
}