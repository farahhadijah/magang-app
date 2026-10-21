
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tempat_pkl', function (Blueprint $table) {
            $table->dropColumn('no_hp');
            $table->string('email')->nullable()->after('jenis_tempat');
        });
    }

    public function down(): void
    {
        Schema::table('tempat_pkl', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->string('no_hp', 15)->nullable()->after('jenis_tempat');
        });
    }
};
