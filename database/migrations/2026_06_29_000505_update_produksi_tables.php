<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update detail_hasil_produksi
        Schema::table('detail_hasil_produksi', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_hasil_produksi', 'jumlah_sak')) {
                $table->integer('jumlah_sak')->default(0)->after('jenis_produk_id');
            }
            if (!Schema::hasColumn('detail_hasil_produksi', 'total_berat_kg')) {
                $table->decimal('total_berat_kg', 10, 2)->default(0)->after('jumlah_sak');
            }
            if (Schema::hasColumn('detail_hasil_produksi', 'jumlah')) {
                $table->dropColumn('jumlah');
            }
        });

        // Buat tabel detail_sak_produksi
        if (!Schema::hasTable('detail_sak_produksi')) {
            Schema::create('detail_sak_produksi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('detail_hasil_produksi_id')->constrained('detail_hasil_produksi')->onDelete('cascade');
                $table->integer('nomor_sak');
                $table->decimal('berat_kg', 10, 2);
                $table->timestamps();
            });
        }

        // Update detail_bahan_produksi
        Schema::table('detail_bahan_produksi', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_bahan_produksi', 'stok_id')) {
                $table->foreignId('stok_id')->nullable()->after('produksi_id')->constrained('stok');
            }
            if (Schema::hasColumn('detail_bahan_produksi', 'berat')) {
                $table->renameColumn('berat', 'berat_kg');
            }
        });

        // Update produksi (hapus jenis_produk_id karena multi produk)
        Schema::table('produksi', function (Blueprint $table) {
            if (Schema::hasColumn('produksi', 'jenis_produk_id')) {
                $table->dropForeign(['jenis_produk_id']);
                $table->dropColumn('jenis_produk_id');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_sak_produksi');
        
        Schema::table('detail_hasil_produksi', function (Blueprint $table) {
            $table->dropColumn(['jumlah_sak', 'total_berat_kg']);
            $table->double('jumlah')->default(0);
        });

        Schema::table('produksi', function (Blueprint $table) {
            $table->foreignId('jenis_produk_id')->nullable()->constrained('jenis_produk');
        });
    }
};