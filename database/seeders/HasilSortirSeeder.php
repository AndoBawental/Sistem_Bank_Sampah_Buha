<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HasilSortirSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hasil_sortir')->insert([
            // Dari Penerimaan 1 (Selesai)
            ['penerimaan_id' => 1, 'jenis_plastik_id' => 1, 'berat_bersih_kg' => 490, 'catatan' => 'Sisa 10kg tanah/residu', 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 1, 'jenis_plastik_id' => 2, 'berat_bersih_kg' => 295, 'catatan' => 'Sisa 5kg residu', 'created_at' => now(), 'updated_at' => now()],
            
            // Dari Penerimaan 2 (Selesai)
            ['penerimaan_id' => 2, 'jenis_plastik_id' => 3, 'berat_bersih_kg' => 390, 'catatan' => '-', 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 2, 'jenis_plastik_id' => 4, 'berat_bersih_kg' => 248, 'catatan' => '-', 'created_at' => now(), 'updated_at' => now()],
            
            // Penerimaan 3 masih 'Proses', kita input sebagian yang sudah selesai disortir
            ['penerimaan_id' => 3, 'jenis_plastik_id' => 1, 'berat_bersih_kg' => 300, 'catatan' => 'Sortir tahap 1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}