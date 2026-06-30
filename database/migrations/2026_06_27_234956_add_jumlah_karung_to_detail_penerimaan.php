<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_penerimaan', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_penerimaan', 'jumlah_karung')) {
                $table->integer('jumlah_karung')->default(0)->after('berat_datang_kg');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detail_penerimaan', function (Blueprint $table) {
            if (Schema::hasColumn('detail_penerimaan', 'jumlah_karung')) {
                $table->dropColumn('jumlah_karung');
            }
        });
    }
};