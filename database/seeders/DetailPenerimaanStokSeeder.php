<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPenerimaanStokSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detail_penerimaan_stok')->insert([
            ['penerimaan_id' => 1, 'jenis_plastik_id' => 1, 'berat' => 500, 'harga' => 8000, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 1, 'jenis_plastik_id' => 2, 'berat' => 300, 'harga' => 7500, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 2, 'jenis_plastik_id' => 3, 'berat' => 400, 'harga' => 7000, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 2, 'jenis_plastik_id' => 4, 'berat' => 250, 'harga' => 8500, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 3, 'jenis_plastik_id' => 1, 'berat' => 600, 'harga' => 8000, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 3, 'jenis_plastik_id' => 2, 'berat' => 350, 'harga' => 7500, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 4, 'jenis_plastik_id' => 5, 'berat' => 450, 'harga' => 7200, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 4, 'jenis_plastik_id' => 6, 'berat' => 200, 'harga' => 7800, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 5, 'jenis_plastik_id' => 3, 'berat' => 380, 'harga' => 7000, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 5, 'jenis_plastik_id' => 4, 'berat' => 420, 'harga' => 8500, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}