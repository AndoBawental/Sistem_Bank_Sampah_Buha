<?php
// database/migrations/2026_04_16_000001_create_stok_adjustment_logs_table.php

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
        Schema::create('stok_adjustment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_id')
                  ->constrained('stok')
                  ->onDelete('cascade')
                  ->comment('Referensi ke tabel stok');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('User yang melakukan adjustment');
            $table->enum('tipe', ['tambah', 'kurang'])
                  ->comment('Tipe adjustment: tambah atau kurang');
            $table->double('berat')
                  ->comment('Berat adjustment (Kg)');
            $table->double('stok_sebelum')
                  ->comment('Stok sebelum adjustment (Kg)');
            $table->double('stok_sesudah')
                  ->comment('Stok setelah adjustment (Kg)');
            $table->string('keterangan', 255)
                  ->nullable()
                  ->comment('Keterangan/alasan adjustment');
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index(['stok_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_adjustment_logs');
    }
};