<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'mitra' to the role enum on users table. Use raw statement for MySQL.
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','mahasiswa','staf','dosen','staff_tu','kaprodi','mitra') NOT NULL DEFAULT 'mahasiswa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum if needed (drop 'mitra')
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','mahasiswa','staf','dosen','staff_tu','kaprodi') NOT NULL DEFAULT 'mahasiswa'");
    }
};