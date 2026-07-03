<?php
// database/migrations/xxxx_add_detail_sak_to_detail_penjualan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->json('detail_sak')->nullable()->after('berat_nett_kg');
        });
    }

    public function down()
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropColumn('detail_sak');
        });
    }
};