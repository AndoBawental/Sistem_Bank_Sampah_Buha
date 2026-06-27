<?php
// database/migrations/xxxx_add_jumlah_karung_to_detail_penerimaan.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJumlahKarungToDetailPenerimaan extends Migration
{
    public function up()
    {
        Schema::table('detail_penerimaan', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_penerimaan', 'jumlah_karung')) {
                $table->integer('jumlah_karung')->default(0)->after('berat_datang_kg');
            }
        });
    }

    public function down()
    {
        Schema::table('detail_penerimaan', function (Blueprint $table) {
            $table->dropColumn('jumlah_karung');
        });
    }
}