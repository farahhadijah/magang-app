<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pengajuan_pkl 
            MODIFY status ENUM(
                'pending',
                'diverifikasi_tu',
                'ditolak_tu',
                'disetujui',
                'ditolak_kaprodi'
            )
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE pengajuan_pkl 
            MODIFY status ENUM(
                'pending',
                'ditolak_tu',
                'disetujui',
                'ditolak_kaprodi'
            )
        ");
    }
};