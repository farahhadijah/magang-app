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

            // Username = NIM / NIDN / NIP
            $table->string('username', 30)->unique();

            // Password login
            $table->string('password');

            // Role sistem (bukan dipilih user)
            $table->enum('role', [
                'admin',
                'mahasiswa',
                'dosen',
                'staff'
            ])->default('mahasiswa');

            // Status akun
            $table->boolean('is_active')->default(true);

            // Login pertama → wajib ganti password
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