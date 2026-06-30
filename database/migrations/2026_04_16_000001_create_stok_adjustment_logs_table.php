<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_adjustment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_id')->constrained('stok')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipe', ['tambah', 'kurang']);
            $table->double('berat');
            $table->double('stok_sebelum');
            $table->double('stok_sesudah');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_adjustment_logs');
    }
};