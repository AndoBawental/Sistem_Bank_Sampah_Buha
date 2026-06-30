<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_bahan_produksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksi')->onDelete('cascade');
            $table->foreignId('stok_id')->nullable()->constrained('stok');
            $table->foreignId('jenis_plastik_id')->constrained('jenis_plastik')->onDelete('cascade');
            $table->decimal('berat_kg', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_bahan_produksi');
    }
};