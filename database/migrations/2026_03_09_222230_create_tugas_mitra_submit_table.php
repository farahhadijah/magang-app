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
        Schema::create('tugas_mitra_submit', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_tugas')
                ->constrained('tugas_mitra')
                ->cascadeOnDelete();

            $table->foreignId('id_pkl')
                ->constrained('pkl')
                ->cascadeOnDelete();

            $table->text('laporan')->nullable();

            $table->string('file')->nullable();

            $table->enum('status', [
                'pending',
                'selesai'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_mitra_submit');
    }
};