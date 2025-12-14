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
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_pkl')->constrained('pengajuan_pkl')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('users');
            $table->enum('level', ['tu', 'kaprodi']);
            $table->enum('status', ['approved', 'rejected']);
            $table->text('catatan')->nullable();
            $table->dateTime('tgl_verifikasi');
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
