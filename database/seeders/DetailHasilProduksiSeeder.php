<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailHasilProduksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detail_hasil_produksi')->insert([
            // Produksi ID 1 (Jenis Produk ID 1)
            ['produksi_id' => 1, 'jenis_produk_id' => 1, 'jumlah' => 280, 'created_at' => now(), 'updated_at' => now()],
            
            // Produksi ID 2 (Jenis Produk ID 2)
            ['produksi_id' => 2, 'jenis_produk_id' => 2, 'jumlah' => 220, 'created_at' => now(), 'updated_at' => now()],
            
            // Produksi ID 3 (Jenis Produk ID 3)
            ['produksi_id' => 3, 'jenis_produk_id' => 3, 'jumlah' => 260, 'created_at' => now(), 'updated_at' => now()],
            
            // Produksi ID 4 (Jenis Produk ID 1)
            ['produksi_id' => 4, 'jenis_produk_id' => 1, 'jumlah' => 240, 'created_at' => now(), 'updated_at' => now()],
            
            // Produksi ID 5 (Jenis Produk ID 4)
            ['produksi_id' => 5, 'jenis_produk_id' => 4, 'jumlah' => 160, 'created_at' => now(), 'updated_at' => now()],
            
            // Tambahan: Produksi ID 1 juga menghasilkan produk lain
            ['produksi_id' => 1, 'jenis_produk_id' => 5, 'jumlah' => 50, 'created_at' => now(), 'updated_at' => now()],
            
            // Tambahan: Produksi ID 3 juga menghasilkan produk lain
            ['produksi_id' => 3, 'jenis_produk_id' => 2, 'jumlah' => 75, 'created_at' => now(), 'updated_at' => now()],
            
            // Data untuk testing stok menipis (< 100 kg)
            ['produksi_id' => 2, 'jenis_produk_id' => 5, 'jumlah' => 45, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}