<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            JenisPlastikSeeder::class,
            JenisProdukSeeder::class,
            SupplierSeeder::class,
            PembeliSeeder::class,
            PenerimaanSeeder::class,
            DetailPenerimaanStokSeeder::class,
            StokSeeder::class,
            ProduksiSeeder::class,
            DetailBahanProduksiSeeder::class,
            DetailHasilProduksiSeeder::class,
            PenjualanSeeder::class,
            DetailPenjualanSeeder::class,
        ]);
    }
}