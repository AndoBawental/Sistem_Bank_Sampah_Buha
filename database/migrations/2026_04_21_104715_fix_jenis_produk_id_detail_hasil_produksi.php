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
        Schema::table('detail_hasil_produksi', function (Blueprint $table) {
    $table->dropForeign(['jenis_produk_id']);

    $table->unsignedBigInteger('jenis_produk_id')->nullable(false)->change();

    $table->foreign('jenis_produk_id')
        ->references('id')
        ->on('jenis_produk')
        ->cascadeOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('detail_hasil_produksi', function (Blueprint $table) {
            //
        });
    }
};
