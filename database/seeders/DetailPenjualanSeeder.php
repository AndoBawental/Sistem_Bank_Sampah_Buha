<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detail_penjualan')->insert([
            ['penjualan_id' => 1, 'jenis_produk_id' => 1, 'qty' => 1000, 'harga' => 17500, 'subtotal' => 17500000, 'created_at' => now(), 'updated_at' => now()],
            
            ['penjualan_id' => 2, 'jenis_produk_id' => 2, 'qty' => 500, 'harga' => 15000, 'subtotal' => 7500000, 'created_at' => now(), 'updated_at' => now()],
            ['penjualan_id' => 2, 'jenis_produk_id' => 3, 'qty' => 500, 'harga' => 12500, 'subtotal' => 6250000, 'created_at' => now(), 'updated_at' => now()],
            
            ['penjualan_id' => 3, 'jenis_produk_id' => 1, 'qty' => 500, 'harga' => 17500, 'subtotal' => 8750000, 'created_at' => now(), 'updated_at' => now()],
            ['penjualan_id' => 3, 'jenis_produk_id' => 2, 'qty' => 500, 'harga' => 15000, 'subtotal' => 7500000, 'created_at' => now(), 'updated_at' => now()],
            
            ['penjualan_id' => 4, 'jenis_produk_id' => 3, 'qty' => 600, 'harga' => 12500, 'subtotal' => 7500000, 'created_at' => now(), 'updated_at' => now()],
            ['penjualan_id' => 4, 'jenis_produk_id' => 4, 'qty' => 600, 'harga' => 12500, 'subtotal' => 7500000, 'created_at' => now(), 'updated_at' => now()],
            
            ['penjualan_id' => 5, 'jenis_produk_id' => 5, 'qty' => 800, 'harga' => 12500, 'subtotal' => 10000000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}