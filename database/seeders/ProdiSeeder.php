<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Fakultas;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $fakultasSaintek = Fakultas::where('nama', 'Fakultas Sains dan Teknologi')->first();

        $prodiList = [
            ['kode' => 'TI', 'nama' => 'Teknik Informatika'],
            ['kode' => 'TE', 'nama' => 'Teknik Elektro'],
            ['kode' => 'TS', 'nama' => 'Teknik Sipil']
        ];

        foreach ($prodiList as $p) {
            DB::table('prodi')->updateOrInsert(
                ['kode' => $p['kode']],
                [
                    'nama' => $p['nama'],
                    'fakultas_id' => $fakultasSaintek->id,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
        }
    }
}