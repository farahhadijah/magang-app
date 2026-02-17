<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tempat_pkl', function (Blueprint $table) {
            $table->string('nama_normalized')->after('nama_tempat');
            $table->index('nama_normalized');
        });
    }

    public function down(): void
    {
        Schema::table('tempat_pkl', function (Blueprint $table) {
            $table->dropIndex(['nama_normalized']);
            $table->dropColumn('nama_normalized');
        });
    }
};