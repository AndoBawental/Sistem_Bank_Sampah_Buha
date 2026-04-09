<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenerimaanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('penerimaan')->insert([
            ['tanggal' => '2025-01-05', 'supplier_id' => 1, 'user_id' => 1, 'keterangan' => 'Penerimaan bahan baku batch 1', 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-10', 'supplier_id' => 2, 'user_id' => 1, 'keterangan' => 'Penerimaan bahan baku batch 2', 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-15', 'supplier_id' => 3, 'user_id' => 1, 'keterangan' => 'Penerimaan bahan baku batch 3', 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-20', 'supplier_id' => 1, 'user_id' => 1, 'keterangan' => 'Penerimaan bahan baku batch 4', 'created_at' => now(), 'updated_at' => now()],
            ['tanggal' => '2025-01-25', 'supplier_id' => 4, 'user_id' => 1, 'keterangan' => 'Penerimaan bahan baku batch 5', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}