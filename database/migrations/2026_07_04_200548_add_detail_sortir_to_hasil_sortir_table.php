<?php
// database/migrations/xxxx_add_detail_sortir_to_hasil_sortir_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hasil_sortir', function (Blueprint $table) {
            $table->json('detail_sortir')->nullable()->after('catatan');
        });
    }

    public function down()
    {
        Schema::table('hasil_sortir', function (Blueprint $table) {
            $table->dropColumn('detail_sortir');
        });
    }
};