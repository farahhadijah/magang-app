<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('laporan_akhir', function (Blueprint $table) {

            // 1️⃣ Tambah opsi rejected
            $table->enum('status_approve', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending')->change();

            // 2️⃣ Tambah kolom approval
            $table->unsignedBigInteger('approved_by')->nullable()->after('catatan_dosen');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_akhir', function (Blueprint $table) {

            // rollback enum
            $table->enum('status_approve', [
                'pending',
                'approved'
            ])->default('pending')->change();

            $table->dropColumn(['approved_by', 'approved_at']);
        });
    }
};