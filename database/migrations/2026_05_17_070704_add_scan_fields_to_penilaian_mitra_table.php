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

            $table->string('file_scan')->nullable();

            $table->timestamp('tgl_upload_scan')->nullable();

            $table->enum('status_scan', [
                'belum_upload',
                'sudah_upload'
            ])->default('belum_upload');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_mitra', function (Blueprint $table) {

            $table->dropColumn([
                'file_scan',
                'tgl_upload_scan',
                'status_scan'
            ]);

        });
    }
};