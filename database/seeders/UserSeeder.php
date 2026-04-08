<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // GUDANG
        $gudang = User::create([
            'name' => 'Gudang',
            'email' => 'gudang@mail.com',
            'password' => Hash::make('password'),
        ]);
        $gudang->assignRole('gudang');

        // PRODUKSI
        $produksi = User::create([
            'name' => 'Produksi',
            'email' => 'produksi@mail.com',
            'password' => Hash::make('password'),
        ]);
        $produksi->assignRole('produksi');

        // PENJUALAN
        $penjualan = User::create([
            'name' => 'Penjualan',
            'email' => 'penjualan@mail.com',
            'password' => Hash::make('password'),
        ]);
        $penjualan->assignRole('penjualan');
    }
}