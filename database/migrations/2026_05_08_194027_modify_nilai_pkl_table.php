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
        Schema::table('nilai_pkl', function (Blueprint $table) {

            // rename nilai -> nilai_angka
            $table->renameColumn('nilai', 'nilai_angka');

            // tambah nilai_huruf
            $table->string('nilai_huruf', 2)
                  ->after('nilai_angka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_pkl', function (Blueprint $table) {

            $table->dropColumn('nilai_huruf');

            $table->renameColumn('nilai_angka', 'nilai');
        });
    }
};