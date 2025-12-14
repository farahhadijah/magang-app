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
        Schema::create('dokumen_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_pkl')->constrained('pengajuan_pkl')->cascadeOnDelete();
            $table->enum('jenis_dokumen', ['KHS', 'Pembayaran', 'StudiTour']);
            $table->string('path_file', 255);
            $table->enum('status_verifikasi', ['pending', 'valid', 'invalid'])->default('pending');
            $table->text('catatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
