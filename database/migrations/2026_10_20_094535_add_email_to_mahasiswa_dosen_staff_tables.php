
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nama');
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nama');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nama');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
