<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        $fakultasList = [
            'Fakultas Sains dan Teknologi',
            'Fakultas Pendidikan',
            'Fakultas Agama Islam'
        ];

        foreach ($fakultasList as $nama) {
            DB::table('fakultas')->updateOrInsert(
                ['nama' => $nama],
                [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}