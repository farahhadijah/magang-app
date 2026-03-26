<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tugas_mitra', function (Blueprint $table) {
            $table->string('file')->nullable()->after('deskripsi');
        });
    }

    public function down()
    {
        Schema::table('tugas_mitra', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }
};