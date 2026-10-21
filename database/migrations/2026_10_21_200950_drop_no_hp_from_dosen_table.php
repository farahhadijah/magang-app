<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            // Hapus kolom no_hp
            $table->dropColumn('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            // Kembalikan kolom jika di-rollback
            $table->string('no_hp', 15)->nullable()->after('jabatan');
        });
    }
};