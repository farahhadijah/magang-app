<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_pkl', function (Blueprint $table) {
            $table->string('semester', 20)
                  ->after('tgl_pengajuan');

            $table->text('alamat_asal')
                  ->after('semester');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_pkl', function (Blueprint $table) {
            $table->dropColumn(['semester', 'alamat_asal']);
        });
    }
};