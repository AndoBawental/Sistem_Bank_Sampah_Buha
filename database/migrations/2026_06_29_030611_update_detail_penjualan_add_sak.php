<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            // Rename harga -> harga_per_kg (jika belum)
            if (Schema::hasColumn('detail_penjualan', 'harga') && !Schema::hasColumn('detail_penjualan', 'harga_per_kg')) {
                $table->renameColumn('harga', 'harga_per_kg');
            }

            // Hapus qty
            if (Schema::hasColumn('detail_penjualan', 'qty')) {
                $table->dropColumn('qty');
            }

            // Tambah kolom baru
            if (!Schema::hasColumn('detail_penjualan', 'jumlah_sak')) {
                $table->integer('jumlah_sak')->default(0)->after('jenis_produk_id');
            }
            if (!Schema::hasColumn('detail_penjualan', 'berat_kirim_kg')) {
                $table->decimal('berat_kirim_kg', 12, 2)->default(0)->after('jumlah_sak');
            }
            if (!Schema::hasColumn('detail_penjualan', 'potongan_persen')) {
                $table->decimal('potongan_persen', 5, 2)->default(0)->after('berat_kirim_kg');
            }
            if (!Schema::hasColumn('detail_penjualan', 'berat_potongan_kg')) {
                $table->decimal('berat_potongan_kg', 12, 2)->default(0)->after('potongan_persen');
            }
            if (!Schema::hasColumn('detail_penjualan', 'berat_nett_kg')) {
                $table->decimal('berat_nett_kg', 12, 2)->default(0)->after('berat_potongan_kg');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $columns = ['jumlah_sak', 'berat_kirim_kg', 'potongan_persen', 'berat_potongan_kg', 'berat_nett_kg'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('detail_penjualan', $col)) {
                    $table->dropColumn($col);
                }
            }

            if (Schema::hasColumn('detail_penjualan', 'harga_per_kg') && !Schema::hasColumn('detail_penjualan', 'harga')) {
                $table->renameColumn('harga_per_kg', 'harga');
            }

            if (!Schema::hasColumn('detail_penjualan', 'qty')) {
                $table->integer('qty')->default(0)->after('jenis_produk_id');
            }
        });
    }
};