<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_sertifikat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pkl_id')
                  ->constrained('pkl')
                  ->cascadeOnDelete();

            $table->date('tanggal_pengajuan');

            $table->string('file_sertifikat')->nullable();

            $table->enum('status', [
                'pending',
                'disetujui',
                'ditolak'
            ])->default('pending');

            $table->text('catatan')->nullable();

            $table->timestamps();

            // 1 PKL hanya boleh 1 pengajuan sertifikat
            $table->unique('pkl_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sertifikat');
    }
};