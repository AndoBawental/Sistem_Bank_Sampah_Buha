<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pembayaran_penerimaan')) {
            Schema::create('pembayaran_penerimaan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penerimaan_id')->constrained('penerimaan')->onDelete('cascade');
                $table->string('metode_bayar')->nullable();
                $table->enum('status_bayar', ['Lunas', 'Hutang'])->default('Lunas');
                $table->date('tanggal_bayar')->nullable();
                $table->string('bukti_bayar')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pembayaran_penerimaan')) {
            Schema::dropIfExists('pembayaran_penerimaan');
        }
    }
};