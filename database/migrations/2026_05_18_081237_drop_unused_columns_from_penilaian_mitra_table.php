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
        Schema::table('penilaian_mitra', function (Blueprint $table) {

            $table->dropColumn('file_pdf_signed');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_mitra', function (Blueprint $table) {

            $table->string('file_pdf_signed')->nullable();

        });
    }
};