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
            $table->enum('jenis_tempat', [
                'Pemerintah',
                'Sekolah',
                'Perguruan Tinggi',
                'Perusahaan',
                'PT',
                'CV',
                'BUMN/BUMD',
                'Yayasan',
                'Organisasi/Lembaga',
                'Rumah Sakit/Klinik',
                'Pesantren',
                'UMKM',
                'Startup',
                'Industri',
                'Lainnya',
            ]);
            $table->string('no_hp', 15);
            $table->text('lokasi_maps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat_pkl');
    }
};