<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_hasil_produksi', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_hasil_produksi', 'jenis_produk_id')) {
                $table->foreignId('jenis_produk_id')
                    ->nullable()
                    ->after('produksi_id')
                    ->constrained('jenis_produk')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('detail_hasil_produksi', function (Blueprint $table) {
            if (Schema::hasColumn('detail_hasil_produksi', 'jenis_produk_id')) {
                $table->dropForeign(['jenis_produk_id']);
                $table->dropColumn('jenis_produk_id');
            }
        });
    }
};