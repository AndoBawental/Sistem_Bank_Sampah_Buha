<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProduksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('produksi')->insert([
            ['tanggal' => '2025-01-07', 'jenis_produk_id' => 1, 'user_id' => 1, 'keterangan' => 'Produksi batch 1', 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-12', 'jenis_produk_id' => 2, 'user_id' => 1, 'keterangan' => 'Produksi batch 2', 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-17', 'jenis_produk_id' => 3, 'user_id' => 1, 'keterangan' => 'Produksi batch 3', 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-22', 'jenis_produk_id' => 1, 'user_id' => 1, 'keterangan' => 'Produksi batch 4', 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-27', 'jenis_produk_id' => 4, 'user_id' => 1, 'keterangan' => 'Produksi batch 5', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}