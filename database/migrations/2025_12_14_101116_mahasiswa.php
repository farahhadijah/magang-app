<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('mahasiswa', function (Blueprint $table) {
    $table->id();

    $table->string('nim', 20)->unique();
    $table->string('nama', 100);

    $table->string('prodi', 100);
    $table->year('angkatan');

    $table->string('no_hp', 15)->nullable();
    $table->boolean('is_active')->default(true);

    $table->timestamps();
});

}
    public function down(): void
    {
        //
    }
};