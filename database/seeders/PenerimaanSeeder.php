<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenerimaanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('penerimaan')->insert([
            // ID 1: Pembelian dari Supplier
            ['tanggal' => '2025-01-05', 'supplier_id' => 2, 'user_id' => 1, 'tipe' => 'Beli', 'status_sortir' => 'Selesai', 'total_berat_kotor_kg' => 800, 'total_bayar' => 6250000, 'keterangan' => 'Penerimaan bahan baku batch 1', 'created_at' => now(), 'updated_at' => now()],
            
            // ID 2: Donasi dari Masyarakat Umum (Supplier ID 1, harga 0)
            ['tanggal' => '2025-01-10', 'supplier_id' => 1, 'user_id' => 1, 'tipe' => 'Donasi', 'status_sortir' => 'Selesai', 'total_berat_kotor_kg' => 650, 'total_bayar' => 0, 'keterangan' => 'Sumbangan warga / Bank Sampah', 'created_at' => now(), 'updated_at' => now()],
            
            // ID 3: Pembelian
            ['tanggal' => '2025-01-15', 'supplier_id' => 4, 'user_id' => 1, 'tipe' => 'Beli', 'status_sortir' => 'Proses', 'total_berat_kotor_kg' => 950, 'total_bayar' => 7425000, 'keterangan' => 'Penerimaan bahan baku batch 3', 'created_at' => now(), 'updated_at' => now()],
            
            // ID 4: Pembelian
            ['tanggal' => '2025-01-20', 'supplier_id' => 2, 'user_id' => 1, 'tipe' => 'Beli', 'status_sortir' => 'Belum', 'total_berat_kotor_kg' => 650, 'total_bayar' => 4800000, 'keterangan' => 'Penerimaan bahan baku batch 4', 'created_at' => now(), 'updated_at' => now()],
            
            // ID 5: Pembelian
            ['tanggal' => '2025-01-25', 'supplier_id' => 5, 'user_id' => 1, 'tipe' => 'Beli', 'status_sortir' => 'Belum', 'total_berat_kotor_kg' => 800, 'total_bayar' => 6230000, 'keterangan' => 'Penerimaan bahan baku batch 5', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}