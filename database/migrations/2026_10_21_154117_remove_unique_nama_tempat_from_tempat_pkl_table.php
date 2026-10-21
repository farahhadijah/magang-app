
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tempat_pkl', function (Blueprint $table) {
            $table->dropUnique(
                'tempat_pkl_nama_tempat_no_hp_unique'
            );
        });
    }

    public function down(): void
    {
        // Indeks UNIQUE lama sengaja tidak dipulihkan,
        // karena bertentangan dengan aturan identitas tempat PKL.
    }
};
