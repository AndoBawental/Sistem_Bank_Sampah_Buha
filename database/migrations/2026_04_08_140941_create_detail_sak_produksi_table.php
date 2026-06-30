<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('detail_sak_produksi')) {
            Schema::create('detail_sak_produksi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('detail_hasil_produksi_id')->constrained('detail_hasil_produksi')->onDelete('cascade');
                $table->integer('nomor_sak');
                $table->decimal('berat_kg', 10, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detail_sak_produksi')) {
            Schema::dropIfExists('detail_sak_produksi');
        }
    }
};