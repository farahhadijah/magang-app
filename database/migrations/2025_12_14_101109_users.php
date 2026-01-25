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
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('username', 30)->unique();
    $table->string('password');
    $table->enum('role', [
        'admin',
        'mahasiswa',
        'staf',
        'dosen',
        'staff_tu',
        'kaprodi'
    ])->default('mahasiswa');
    
    // Hanya kolom, TANPA foreign key constraint
    $table->unsignedBigInteger('mahasiswa_id')->nullable();
    $table->unsignedBigInteger('dosen_id')->nullable();
    $table->unsignedBigInteger('staff_id')->nullable();
    
    $table->boolean('is_active')->default(true);
    $table->boolean('first_login')->default(true);
    $table->rememberToken();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};