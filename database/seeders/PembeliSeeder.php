<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembeliSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pembeli')->insert([
            ['nama' => 'PT Indah Karya Pratama', 'alamat' => 'Jl. Raya Kalirungkut No. 23, Surabaya', 'telepon' => '081234123456', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'CV Sumber Makmur', 'alamat' => 'Jl. Ahmad Yani No. 45, Sidoarjo', 'telepon' => '081345234567', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'UD Berkah Abadi', 'alamat' => 'Jl. Diponegoro No. 67, Malang', 'telepon' => '081456345678', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'PT Karya Utama Plastik', 'alamat' => 'Jl. Raya Darmo No. 89, Surabaya', 'telepon' => '081567456789', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'CV Mandiri Jaya', 'alamat' => 'Jl. Prapen No. 34, Surabaya', 'telepon' => '081678567890', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}