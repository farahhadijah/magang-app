<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user mahasiswa
        $user = User::where('role', 'mahasiswa')->first();

        // Jika belum ada user mahasiswa, hentikan
        if (!$user) {
            return;
        }
        Mahasiswa::updateOrCreate(
            ['nim' => '112310090'],
            [
                'nama' => 'Nur Faizah', // ✅ WAJIB
                'prodi_id'  => 1,
                'angkatan' => 2023,
                'no_hp' => '08123456789',
                'is_active' => true,
            ]
        );
    }
}