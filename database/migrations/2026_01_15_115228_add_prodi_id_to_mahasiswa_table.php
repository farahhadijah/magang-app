<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {

            // 1. Tambah kolom prodi_id
            $table->foreignId('prodi_id')
                ->after('nama')
                ->constrained('prodi')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // 2. Hapus kolom prodi lama (string)
            $table->dropColumn('prodi');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('prodi', 100);
            $table->dropForeign(['prodi_id']);
            $table->dropColumn('prodi_id');
        });
    }
};