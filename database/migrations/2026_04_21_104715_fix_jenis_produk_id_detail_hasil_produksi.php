<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_hasil_produksi', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['jenis_produk_id']);

            // Ubah menjadi required
            $table->unsignedBigInteger('jenis_produk_id')->nullable(false)->change();

            // Tambah foreign key baru dengan cascade delete
            $table->foreign('jenis_produk_id')
                ->references('id')
                ->on('jenis_produk')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detail_hasil_produksi', function (Blueprint $table) {
            $table->dropForeign(['jenis_produk_id']);
            
            $table->unsignedBigInteger('jenis_produk_id')->nullable()->change();
            
            $table->foreign('jenis_produk_id')
                ->references('id')
                ->on('jenis_produk')
                ->nullOnDelete();
        });
    }
};