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
        Schema::create('tempat_pkl', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tempat', 150);
            $table->enum('jenis_tempat', ['Pemerintah', 'Sekolah', 'PT', 'CV']);
            $table->string('no_hp', 15);
            $table->text('lokasi_maps');
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
