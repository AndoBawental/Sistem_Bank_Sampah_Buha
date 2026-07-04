<?php
// database/migrations/xxxx_add_detail_hasil_produksi_id_to_detail_bahan_produksi.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('detail_bahan_produksi', function (Blueprint $table) {
            $table->unsignedBigInteger('detail_hasil_produksi_id')->nullable()->after('produksi_id');
            $table->foreign('detail_hasil_produksi_id')->references('id')->on('detail_hasil_produksi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('detail_bahan_produksi', function (Blueprint $table) {
            $table->dropForeign(['detail_hasil_produksi_id']);
            $table->dropColumn('detail_hasil_produksi_id');
        });
    }
};