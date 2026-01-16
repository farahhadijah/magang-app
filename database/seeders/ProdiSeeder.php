<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $prodiList = [
            ['kode' => 'TI', 'nama' => 'Teknik Informatika'],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi'],
            ['kode' => 'MI', 'nama' => 'Manajemen Informatika']
        ];

        foreach ($prodiList as $p) {
            DB::table('prodi')->updateOrInsert(
                ['kode' => $p['kode']],
                [
                    'nama' => $p['nama'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}