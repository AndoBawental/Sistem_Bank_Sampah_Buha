<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('hasil_sortir')) {
            Schema::create('hasil_sortir', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penerimaan_id')->constrained('penerimaan')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('jenis_plastik_id')->constrained('jenis_plastik');
                $table->double('berat_bersih_kg');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('hasil_sortir')) {
            Schema::dropIfExists('hasil_sortir');
        }
    }
};