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
        Schema::create('pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_pkl')->constrained('pengajuan_pkl');
            $table->foreignId('id_dosen')->constrained('users');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
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
