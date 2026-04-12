<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel ini hanya diisi jika penerimaan->tipe == 'Beli'
        Schema::create('pembayaran_penerimaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->constrained('penerimaan')->cascadeOnDelete();
            $table->string('metode_bayar')->nullable(); // Tunai, Transfer
            $table->enum('status_bayar', ['Lunas', 'Hutang'])->default('Lunas');
            $table->date('tanggal_bayar')->nullable();
            $table->string('bukti_bayar')->nullable(); // Path ke file foto
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_penerimaan');
    }
};