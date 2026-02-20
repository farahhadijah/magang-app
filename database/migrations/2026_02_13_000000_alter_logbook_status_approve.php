<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL: change enum to include 'revisi'. Use raw statement because Blueprint->enum->change
        // is not universally supported across DB drivers.
        DB::statement(
            "ALTER TABLE `logbook` MODIFY `status_approve` ENUM('pending','approved','revisi') NOT NULL DEFAULT 'pending'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum (pending, approved)
        DB::statement(
            "ALTER TABLE `logbook` MODIFY `status_approve` ENUM('pending','approved') NOT NULL DEFAULT 'pending'"
        );
    }
};
