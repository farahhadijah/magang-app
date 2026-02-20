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
        Schema::table('tempat_pkl', function (Blueprint $table) {
            $table->unique(['nama_tempat', 'no_hp']);
        });
    }

    public function down()
    {
        Schema::table('tempat_pkl', function (Blueprint $table) {
            $table->dropUnique(['nama_tempat', 'no_hp']);
        });
    }

};