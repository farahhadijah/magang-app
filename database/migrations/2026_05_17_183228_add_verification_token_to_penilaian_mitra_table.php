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
        Schema::table('penilaian_mitra', function (Blueprint $table) {

            // hapus kolom scan
            $table->dropColumn([
                'file_scan',
                'tgl_upload_scan',
                'status_scan'
            ]);

            // token verifikasi QR
            $table->string('verification_token')
                ->nullable()
                ->unique()
                ->after('grade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_mitra', function (Blueprint $table) {

            // kembalikan kolom scan
            $table->string('file_scan')
                ->nullable();

            $table->timestamp('tgl_upload_scan')
                ->nullable();

            $table->enum('status_scan', [
                'belum_upload',
                'sudah_upload'
            ])->default('belum_upload');

            // hapus token
            $table->dropColumn('verification_token');

        });
    }
};