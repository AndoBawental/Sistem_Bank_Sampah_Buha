<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            
            // Relasi
            $table->foreignId('supplier_id')->constrained('supplier'); 
            $table->foreignId('user_id')->constrained('users'); // Petugas/Admin
            
            // Status & Tipe
            $table->enum('tipe', ['Beli', 'Donasi'])->default('Beli');
            $table->enum('status_sortir', ['Belum', 'Proses', 'Selesai'])->default('Belum');
            
            // Rekapan
            $table->double('total_berat_kotor_kg')->default(0);
            $table->decimal('total_bayar', 15, 2)->default(0);
            
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan');
    }
};