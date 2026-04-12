<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Setup Awal
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            JenisPlastikSeeder::class,
            JenisProdukSeeder::class,
            PembeliSeeder::class,
            
            // 2. Modul Supplier & Penerimaan (Update)
            SupplierSeeder::class,
            PenerimaanSeeder::class,
            DetailPenerimaanSeeder::class,      // Menggantikan DetailPenerimaanStok
            HasilSortirSeeder::class,           // File Baru
            PembayaranPenerimaanSeeder::class,  // File Baru
            
            // 3. Modul Gudang, Produksi & Penjualan
            StokSeeder::class,
            ProduksiSeeder::class,
            DetailBahanProduksiSeeder::class,
            DetailHasilProduksiSeeder::class,
            PenjualanSeeder::class,
            DetailPenjualanSeeder::class,
        ]);
    }
}