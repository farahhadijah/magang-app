<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan foreign key constraints
            $table->foreign('mahasiswa_id')
                  ->references('id')
                  ->on('mahasiswa')
                  ->onDelete('cascade');
                  
            $table->foreign('dosen_id')
                  ->references('id')
                  ->on('dosen')
                  ->onDelete('cascade');
                  
            $table->foreign('staff_id')
                  ->references('id')
                  ->on('staff')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropForeign(['dosen_id']);
            $table->dropForeign(['staff_id']);
        });
    }
};