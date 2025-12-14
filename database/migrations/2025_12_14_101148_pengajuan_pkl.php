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
        Schema::create('pengajuan_pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mhs')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('id_tempat_pkl')->constrained('tempat_pkl');
            $table->enum('status', [
                'draft',
                'pending',
                'ditolak_tu',
                'ditolak_kaprodi',
                'disetujui'
            ])->default('draft');
            $table->text('catatan_tu')->nullable();
            $table->text('catatan_kaprodi')->nullable();
            $table->date('tgl_pengajuan')->nullable();
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
