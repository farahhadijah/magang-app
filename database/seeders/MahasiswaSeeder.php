<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = DB::table('prodi')->where('kode', 'TI')->first();

        Mahasiswa::updateOrCreate(
            ['nim' => '112310090'],
            [
                'nama' => 'Nur Faizah',
                'prodi_id' => $prodi->id,
                'angkatan' => 2023,
                'no_hp' => '08123456789',
                'is_active' => true,
            ]
        );
    }
}