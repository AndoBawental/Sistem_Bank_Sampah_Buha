<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('penjualan')->insert([
            ['tanggal' => '2025-01-08', 'pembeli_id' => 1, 'user_id' => 1, 'total_harga' => 17500000, 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-13', 'pembeli_id' => 2, 'user_id' => 1, 'total_harga' => 13750000, 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-18', 'pembeli_id' => 3, 'user_id' => 1, 'total_harga' => 16250000, 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-23', 'pembeli_id' => 4, 'user_id' => 1, 'total_harga' => 15000000, 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-28', 'pembeli_id' => 5, 'user_id' => 1, 'total_harga' => 10000000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}