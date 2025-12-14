<?php

namespace Database\Seeders;

use App\Models\TempatPkl;
use Illuminate\Database\Seeder;

class TempatPklSeeder extends Seeder
{
    public function run(): void
    {
        TempatPkl::create([
            'nama_tempat' => 'PT Teknologi Nusantara',
            'jenis_tempat' => 'PT',
            'no_hp' => '021888888',
            'lokasi_maps' => 'https://maps.google.com'
        ]);
    }
}
