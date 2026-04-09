<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stok')->insert([
            ['jenis_plastik_id' => 1, 'total_berat' => 1100, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_plastik_id' => 2, 'total_berat' => 650, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_plastik_id' => 3, 'total_berat' => 780, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_plastik_id' => 4, 'total_berat' => 670, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_plastik_id' => 5, 'total_berat' => 450, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_plastik_id' => 6, 'total_berat' => 200, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}