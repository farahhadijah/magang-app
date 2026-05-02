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
    Schema::table('pkl', function (Blueprint $table) {
        $table->dropForeign(['id_dosen']); // hapus FK lama ke users

        $table->foreign('id_dosen')
            ->references('id')
            ->on('dosen')
            ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('pkl', function (Blueprint $table) {
        $table->dropForeign(['id_dosen']);

        $table->foreign('id_dosen')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
    });
}
};