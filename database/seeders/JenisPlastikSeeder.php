<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPlastikSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_plastik')->insert([
            ['nama' => 'PET', 'keterangan' => 'Polyethylene Terephthalate (biasa untuk botol minuman)', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'HDPE', 'keterangan' => 'High Density Polyethylene (botol susu, jerigen)', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'LDPE', 'keterangan' => 'Low Density Polyethylene (plastik kresek)', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'PP', 'keterangan' => 'Polypropylene (sedotan, tutup botol)', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'PS', 'keterangan' => 'Polystyrene (styrofoam)', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'PVC', 'keterangan' => 'Polyvinyl Chloride (pipa, kabel)', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}