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
            // Ubah enum status_approval dari 3 opsi menjadi 2 opsi (hapus 'rejected')
            // Karena kita tidak bisa langsung modify enum, kita drop dan recreate
            $table->dropColumn('status_approval');
            $table->dropColumn('tgl_approval');
            $table->dropColumn('catatan_approval');
        });

        Schema::table('nilai_pkl', function (Blueprint $table) {
            // Tambah field baru dengan hanya 2 status (pending, approved)
            $table->enum('status_approval', ['pending', 'approved'])
                  ->default('pending')
                  ->after('nilai_huruf');

            // Tambah field tgl_approval untuk mencatat kapan di-approve
            $table->timestamp('tgl_approval')
                  ->nullable()
                  ->after('status_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_pkl', function (Blueprint $table) {
            $table->dropColumn(['status_approval', 'tgl_approval']);
        });

        Schema::table('nilai_pkl', function (Blueprint $table) {
            // Restore ke kondisi awal dengan 3 status
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('nilai_huruf');

            $table->timestamp('tgl_approval')
                  ->nullable()
                  ->after('status_approval');

            $table->text('catatan_approval')
                  ->nullable()
                  ->after('tgl_approval');
        });
    }
};
