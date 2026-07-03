<?php
// database/migrations/xxxx_xx_xx_add_detail_karung_to_penerimaan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penerimaan', function (Blueprint $table) {
            $table->json('detail_karung')->nullable()->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('penerimaan', function (Blueprint $table) {
            $table->dropColumn('detail_karung');
        });
    }
};