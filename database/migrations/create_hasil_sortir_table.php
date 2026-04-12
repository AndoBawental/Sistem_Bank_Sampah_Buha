<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Data di tabel ini yang akan mentrigger penambahan stok gudang
        Schema::create('hasil_sortir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->constrained('penerimaan')->cascadeOnDelete();
            $table->foreignId('jenis_plastik_id')->constrained('jenis_plastik');
            
            $table->double('berat_bersih_kg'); // Berat aktual setelah kotoran dibuang
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_sortir');
    }
};