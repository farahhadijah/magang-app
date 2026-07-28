<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')
                ->nullable()
                ->change();
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')
                ->nullable(false)
                ->change();
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')
                ->nullable(false)
                ->change();
        });
    }
};