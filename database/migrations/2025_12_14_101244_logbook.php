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
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_pkl')
                ->constrained('pkl')
                ->cascadeOnDelete();

            $table->date('tgl');

            $table->text('kegiatan');

            $table->enum('status_approve', [
                'pending',
                'approved',
                'revisi'
            ])->default('pending');

            $table->text('catatan')->nullable();

            $table->text('link_dokumentasi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbook');
    }
};