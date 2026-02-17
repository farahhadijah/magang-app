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
        Schema::table('mitra', function (Blueprint $table) {
            $table->dropColumn('nama_perwakilan');
        });
    }

    public function down()
    {
        Schema::table('mitra', function (Blueprint $table) {
            $table->string('nama_perwakilan');
        });
    }

};