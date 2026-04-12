<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('supplier')->insert([
            // Wajib ada untuk donasi/non-member
            ['id' => 1, 'nama' => 'Masyarakat Umum / Non-Member', 'alamat' => '-', 'telepon' => '-', 'created_at' => now(), 'updated_at' => now()],
            
            // Data Supplier Anda (Mulai dari ID 2)
            ['id' => 2, 'nama' => 'PT Maju Jaya Plastik', 'alamat' => 'Jl. Raya Industri No. 45, Surabaya', 'telepon' => '081234567890', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'CV Sumber Rejeki', 'alamat' => 'Jl. Pahlawan No. 12, Sidoarjo', 'telepon' => '081345678901', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'UD Plastik Indah', 'alamat' => 'Jl. Gatot Subroto No. 78, Malang', 'telepon' => '081456789012', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'PT Bumi Plastik Nusantara', 'alamat' => 'Jl. Industri No. 23, Gresik', 'telepon' => '081567890123', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'CV Karya Mandiri', 'alamat' => 'Jl. Raya Kedung Cowek No. 56, Surabaya', 'telepon' => '081678901234', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}