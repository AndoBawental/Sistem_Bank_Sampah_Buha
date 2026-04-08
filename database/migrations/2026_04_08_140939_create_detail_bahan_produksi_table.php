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
       Schema::create('detail_bahan_produksi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('produksi_id')->constrained('produksi')->cascadeOnDelete();
    $table->foreignId('jenis_plastik_id')->constrained('jenis_plastik')->cascadeOnDelete();
    $table->double('berat');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_bahan_produksi');
    }
};
