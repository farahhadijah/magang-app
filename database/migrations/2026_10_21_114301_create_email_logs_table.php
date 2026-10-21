<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();

            // Relasi utama
            $table->foreignId('mitra_id')
                ->nullable()
                ->constrained('mitra')
                ->nullOnDelete();

            $table->foreignId('tempat_pkl_id')
                ->nullable()
                ->constrained('tempat_pkl')
                ->nullOnDelete();

            // Snapshot data saat email dikirim
            // (penting: kalau email tempat_pkl berubah nanti,
            //  kita tetap tahu dulu dikirim ke alamat mana)
            $table->string('email_tujuan');

            // Jenis pengiriman
            // akun_baru  = otomatis saat kaprodi approve
            // resend     = kirim ulang tanpa ubah apa pun
            // edit_email = email tujuan diubah lalu dikirim
            // reset_password = password baru lalu dikirim
            $table->enum('jenis', [
                'akun_baru',
                'resend',
                'edit_email',
                'reset_password',
            ]);

            // Status pengiriman
            // pending = belum terkirim (mis. email kosong)
            // sent    = berhasil
            // failed  = gagal kirim
            $table->enum('status', ['pending', 'sent', 'failed'])
                ->default('pending');

            // Pesan error kalau failed / keterangan pending
            $table->text('error_message')->nullable();

            // Siapa staff yang trigger (null = sistem/kaprodi)
            $table->foreignId('triggered_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Index untuk query cepat
            $table->index(['mitra_id', 'created_at']);
            $table->index(['tempat_pkl_id', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};