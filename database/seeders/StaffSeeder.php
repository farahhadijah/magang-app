<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\Prodi;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil prodi berdasarkan kode (lebih aman daripada pakai angka langsung)
        $prodi = Prodi::where('kode', 'TI')->first(); 
        // Ganti 'TI' sesuai kode prodi yang ada di database kamu

        if (! $prodi) {
            $this->command->error('Prodi tidak ditemukan. Jalankan ProdiSeeder terlebih dahulu.');
            return;
        }

        Staff::updateOrCreate(
            ['nip' => '19800101'], // UNIQUE
            [
                'nama'       => 'Admin Akademik',
                'no_hp'      => '08123456789',
                'is_active'  => true,
                'prodi_id'   => $prodi->id, // WAJIB SEKARANG
            ]
        );
    }
}