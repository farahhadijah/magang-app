<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('mahasiswa', function (Blueprint $table) {
    $table->id();

    $table->string('nim', 20)->unique();
    $table->string('nama', 100);

    $table->string('prodi', 100); // nanti bisa jadi FK
    $table->year('angkatan');

    $table->boolean('is_active')->default(true);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pkl', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};