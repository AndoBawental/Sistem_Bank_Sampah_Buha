<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailBahanProduksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detail_bahan_produksi')->insert([
            ['produksi_id' => 1, 'jenis_plastik_id' => 1, 'berat' => 200, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 1, 'jenis_plastik_id' => 2, 'berat' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 2, 'jenis_plastik_id' => 3, 'berat' => 150, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 2, 'jenis_plastik_id' => 4, 'berat' => 80, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 3, 'jenis_plastik_id' => 1, 'berat' => 180, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 3, 'jenis_plastik_id' => 5, 'berat' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 4, 'jenis_plastik_id' => 2, 'berat' => 120, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 4, 'jenis_plastik_id' => 3, 'berat' => 130, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 5, 'jenis_plastik_id' => 4, 'berat' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 5, 'jenis_plastik_id' => 6, 'berat' => 70, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}