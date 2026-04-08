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
       Schema::create('detail_penerimaan_stok', function (Blueprint $table) {
    $table->id();
    $table->foreignId('penerimaan_id')->constrained('penerimaan')->cascadeOnDelete();
    $table->foreignId('jenis_plastik_id')->constrained('jenis_plastik')->cascadeOnDelete();
    $table->double('berat'); // kg
    $table->decimal('harga', 12, 2)->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penerimaan_stok');
    }
};
