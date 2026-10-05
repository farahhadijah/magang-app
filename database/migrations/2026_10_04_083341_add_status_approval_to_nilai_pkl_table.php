<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nilai_pkl', function (Blueprint $table) {
            // Tambah field status_approval (pending, approved, rejected)
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('nilai_huruf');

            // Tambah field tgl_approval untuk mencatat kapan di-approve
            $table->timestamp('tgl_approval')
                  ->nullable()
                  ->after('status_approval');

            // Tambah field catatan_approval untuk alasan reject (opsional)
            $table->text('catatan_approval')
                  ->nullable()
                  ->after('tgl_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_pkl', function (Blueprint $table) {
            $table->dropColumn(['status_approval', 'tgl_approval', 'catatan_approval']);
        });
    }
};