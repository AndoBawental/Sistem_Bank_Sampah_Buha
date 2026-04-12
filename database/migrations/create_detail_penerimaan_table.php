<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel barang mentah yang baru datang dari truk/motor
        Schema::create('detail_penerimaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->constrained('penerimaan')->cascadeOnDelete();
            $table->foreignId('jenis_plastik_id')->constrained('jenis_plastik');
            
            $table->double('berat_datang_kg');
            $table->decimal('harga_per_kg', 12, 2)->default(0); // 0 jika Donasi
            $table->decimal('subtotal', 15, 2)->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penerimaan');
    }
};