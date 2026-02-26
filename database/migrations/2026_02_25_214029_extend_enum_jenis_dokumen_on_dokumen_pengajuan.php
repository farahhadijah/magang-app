<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            ALTER TABLE dokumen_pengajuan
            MODIFY jenis_dokumen
            ENUM(
                'KHS',
                'Pembayaran',
                'StudiTour',
                'FormPKN',
                'KRSRemedial'
            ) NOT NULL
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE dokumen_pengajuan
            MODIFY jenis_dokumen
            ENUM(
                'KHS',
                'Pembayaran',
                'StudiTour'
            ) NOT NULL
        ");
    }
};