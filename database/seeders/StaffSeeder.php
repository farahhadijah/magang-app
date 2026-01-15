<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        Staff::updateOrCreate(
            ['nip' => '19800101'], // UNIQUE
            [
                'nama' => 'Admin Akademik',
                'jabatan' => 'Staff TU',
                'no_hp' => '08123456789',
                'is_active' => true,
            ]
        );

        Staff::updateOrCreate(
            ['nip' => '19830512'],
            [
                'nama' => 'Siti Aminah',
                'jabatan' => 'Staff Fakultas',
                'no_hp' => '08129876543',
                'is_active' => true,
            ]
        );
    }
}