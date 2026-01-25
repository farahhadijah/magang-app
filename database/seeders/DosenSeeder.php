<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = DB::table('prodi')->where('kode', 'TI')->first();

        Dosen::updateOrCreate(
            ['nidn' => '12001234'],
            [
                'nama' => 'Dr. Budi Santoso',
                'prodi_id' => $prodi->id,
                'no_hp' => '081234567890',
                'is_active' => true,
            ]
        );
    }
}