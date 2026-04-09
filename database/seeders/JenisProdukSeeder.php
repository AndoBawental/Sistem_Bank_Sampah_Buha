<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisProdukSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_produk')->insert([
            ['nama' => 'Pelet Plastik Daur Ulang', 'keterangan' => 'Pelet hasil daur ulang plastik', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bijih Plastik', 'keterangan' => 'Bijih plastik siap cetak', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Plastik Daur Ulang Grade A', 'keterangan' => 'Kualitas terbaik', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Plastik Daur Ulang Grade B', 'keterangan' => 'Kualitas sedang', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Flakes Plastik', 'keterangan' => 'Serpihan plastik hasil cacahan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}