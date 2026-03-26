<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // perbaiki data lama dulu
        DB::table('users')
            ->where('role','staf')
            ->update(['role' => 'staff_tu']);

        DB::table('users')
            ->where('role','kaprodi')
            ->update(['role' => 'dosen']);

        // baru ubah enum
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'admin',
                'mahasiswa',
                'dosen',
                'staff_tu',
                'mitra'
            ) NOT NULL DEFAULT 'mahasiswa'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'admin',
                'mahasiswa',
                'staf',
                'dosen',
                'staff_tu',
                'kaprodi',
                'mitra'
            )
        ");
    }
};