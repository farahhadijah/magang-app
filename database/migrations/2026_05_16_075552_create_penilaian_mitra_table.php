<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_mitra', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_pkl')
                ->unique()
                ->constrained('pkl')
                ->cascadeOnDelete();

            // aspek penilaian
            $table->unsignedTinyInteger('kedisiplinan');
            $table->unsignedTinyInteger('kreativitas');
            $table->unsignedTinyInteger('ketekunan');
            $table->unsignedTinyInteger('kerjasama');
            $table->unsignedTinyInteger('kejujuran');
            $table->unsignedTinyInteger('kesopanan');
            $table->unsignedTinyInteger('semangat_kerja');
            $table->unsignedTinyInteger('kedalaman_materi');

            // hasil akhir
            $table->decimal('rata_rata', 5, 2);
            $table->char('grade', 1);

            // file pdf sistem
            $table->string('file_pdf')->nullable();

            // hasil scan ttd + stempel
            $table->string('file_pdf_signed')->nullable();

            // tanggal submit nilai
            $table->date('tgl_input');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_mitra');
    }
};