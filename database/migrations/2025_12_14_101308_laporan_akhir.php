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
        Schema::create('laporan_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pkl')->constrained('pkl')->cascadeOnDelete();
            $table->string('path_file', 255);
            $table->enum('status_approve', ['pending', 'approved'])->default('pending');
            $table->text('catatan_dosen')->nullable();
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
