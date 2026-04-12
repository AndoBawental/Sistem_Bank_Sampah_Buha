<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPenerimaanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detail_penerimaan')->insert([
            // Penerimaan 1 (Beli)
            ['penerimaan_id' => 1, 'jenis_plastik_id' => 1, 'berat_datang_kg' => 500, 'harga_per_kg' => 8000, 'subtotal' => 4000000, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 1, 'jenis_plastik_id' => 2, 'berat_datang_kg' => 300, 'harga_per_kg' => 7500, 'subtotal' => 2250000, 'created_at' => now(), 'updated_at' => now()],
            
            // Penerimaan 2 (Donasi - Harga 0)
            ['penerimaan_id' => 2, 'jenis_plastik_id' => 3, 'berat_datang_kg' => 400, 'harga_per_kg' => 0, 'subtotal' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 2, 'jenis_plastik_id' => 4, 'berat_datang_kg' => 250, 'harga_per_kg' => 0, 'subtotal' => 0, 'created_at' => now(), 'updated_at' => now()],
            
            // Penerimaan 3 (Beli)
            ['penerimaan_id' => 3, 'jenis_plastik_id' => 1, 'berat_datang_kg' => 600, 'harga_per_kg' => 8000, 'subtotal' => 4800000, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 3, 'jenis_plastik_id' => 2, 'berat_datang_kg' => 350, 'harga_per_kg' => 7500, 'subtotal' => 2625000, 'created_at' => now(), 'updated_at' => now()],
            
            // Penerimaan 4 (Beli)
            ['penerimaan_id' => 4, 'jenis_plastik_id' => 5, 'berat_datang_kg' => 450, 'harga_per_kg' => 7200, 'subtotal' => 3240000, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 4, 'jenis_plastik_id' => 6, 'berat_datang_kg' => 200, 'harga_per_kg' => 7800, 'subtotal' => 1560000, 'created_at' => now(), 'updated_at' => now()],
            
            // Penerimaan 5 (Beli)
            ['penerimaan_id' => 5, 'jenis_plastik_id' => 3, 'berat_datang_kg' => 380, 'harga_per_kg' => 7000, 'subtotal' => 2660000, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 5, 'jenis_plastik_id' => 4, 'berat_datang_kg' => 420, 'harga_per_kg' => 8500, 'subtotal' => 3570000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}