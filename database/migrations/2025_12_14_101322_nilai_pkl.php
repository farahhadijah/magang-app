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
        Schema::create('nilai_pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pkl')->constrained('pkl')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->string('keterangan', 100)->nullable();
            $table->date('tgl_input');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
