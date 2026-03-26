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
        Schema::table('tugas_mitra_submit', function (Blueprint $table) {

            $table->boolean('revisi')
                ->default(false)
                ->after('status');

            $table->text('catatan_revisi')
                ->nullable()
                ->after('revisi');

        });
    }

    public function down()
    {
        Schema::table('tugas_mitra_submit', function (Blueprint $table) {

            $table->dropColumn(['revisi','catatan_revisi']);

        });
    }
};