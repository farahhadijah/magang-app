<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pkl', function (Blueprint $table) {

            // 1️⃣ Ubah tgl_selesai jadi nullable
            $table->date('tgl_selesai')->nullable()->change();

            // 2️⃣ Ubah enum status
            $table->enum('status', [
                'aktif',
                'menunggu_laporan',
                'selesai'
            ])->default('aktif')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pkl', function (Blueprint $table) {

            // rollback ke versi lama
            $table->date('tgl_selesai')->nullable(false)->change();

            $table->enum('status', [
                'aktif',
                'selesai'
            ])->default('aktif')->change();
        });
    }
};