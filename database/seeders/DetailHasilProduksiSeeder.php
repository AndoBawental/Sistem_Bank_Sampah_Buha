<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailHasilProduksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detail_hasil_produksi')->insert([
            ['produksi_id' => 1, 'jumlah' => 280, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 2, 'jumlah' => 220, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 3, 'jumlah' => 260, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 4, 'jumlah' => 240, 'created_at' => now(), 'updated_at' => now()],
            ['produksi_id' => 5, 'jumlah' => 160, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}