<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 🔥 1. Update data lama dulu (jika ada)
        DB::statement("
            UPDATE dokumen_pengajuan 
            SET jenis_dokumen = 'KRS' 
            WHERE jenis_dokumen = 'KRSRemedial'
        ");

        // 🔥 2. Ubah ENUM
        DB::statement("
            ALTER TABLE dokumen_pengajuan 
            MODIFY jenis_dokumen 
            ENUM('KHS','Pembayaran','StudiTour','FormPKN','KRS') NOT NULL
        ");
    }

    public function down(): void
    {
        // 🔁 rollback ke kondisi lama
        DB::statement("
            ALTER TABLE dokumen_pengajuan 
            MODIFY jenis_dokumen 
            ENUM('KHS','Pembayaran','StudiTour','FormPKN','KRSRemedial') NOT NULL
        ");

        DB::statement("
            UPDATE dokumen_pengajuan 
            SET jenis_dokumen = 'KRSRemedial' 
            WHERE jenis_dokumen = 'KRS'
        ");
    }
};