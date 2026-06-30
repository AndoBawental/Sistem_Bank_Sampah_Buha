<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('detail_penerimaan')) {
            Schema::create('detail_penerimaan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penerimaan_id')->constrained('penerimaan')->onDelete('cascade');
                $table->foreignId('jenis_plastik_id')->constrained('jenis_plastik');
                $table->double('berat_datang_kg');
                $table->integer('jumlah_karung')->default(0);
                $table->decimal('harga_per_kg', 12, 2)->default(0);
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Jangan drop tabel jika ada data
        if (Schema::hasTable('detail_penerimaan')) {
            Schema::dropIfExists('detail_penerimaan');
        }
    }
};