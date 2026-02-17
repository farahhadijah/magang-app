<?php

namespace Database\Seeders;

use App\Models\TempatPkl;
use Illuminate\Database\Seeder;

class TempatPklSeeder extends Seeder
{
    public function run(): void
    {
        $nama = 'PT Teknologi Nusantara'; 
        TempatPkl::create([
        'nama_tempat'     => $nama,
        'nama_normalized' => strtolower(preg_replace('/[^a-z0-9\s]/', '', $nama)),
        'jenis_tempat'    => 'PT',
        'no_hp'           => '021888888',
        'lokasi_maps'     => 'https://maps.google.com',
    ]);
    }
}