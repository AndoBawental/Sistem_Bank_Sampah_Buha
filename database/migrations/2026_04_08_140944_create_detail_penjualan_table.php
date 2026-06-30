<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('cascade');
            $table->foreignId('jenis_produk_id')->constrained('jenis_produk')->onDelete('cascade');
            $table->integer('jumlah_sak')->default(0);
            $table->decimal('berat_kirim_kg', 12, 2)->default(0);
            $table->decimal('potongan_persen', 5, 2)->default(0);
            $table->decimal('berat_potongan_kg', 12, 2)->default(0);
            $table->decimal('berat_nett_kg', 12, 2)->default(0);
            $table->decimal('harga_per_kg', 12, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};