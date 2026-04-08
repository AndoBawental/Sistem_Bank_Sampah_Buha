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
        Schema::create('detail_penjualan', function (Blueprint $table) {
    $table->id();
    $table->foreignId('penjualan_id')->constrained('penjualan')->cascadeOnDelete();
    $table->foreignId('jenis_produk_id')->constrained('jenis_produk')->cascadeOnDelete();
    $table->integer('qty');
    $table->decimal('harga', 12, 2);
    $table->decimal('subtotal', 15, 2);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
