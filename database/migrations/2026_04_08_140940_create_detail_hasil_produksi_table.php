<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_hasil_produksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksi')->onDelete('cascade');
            $table->foreignId('jenis_produk_id')->constrained('jenis_produk')->onDelete('cascade');
            $table->integer('jumlah_sak')->default(0);
            $table->decimal('total_berat_kg', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_hasil_produksi');
    }
};