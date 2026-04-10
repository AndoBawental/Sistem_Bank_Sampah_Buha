<?php
// app/Models/JenisProduk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DetailPenjualan;
class JenisProduk extends Model
{
    use HasFactory;

    protected $table = 'jenis_produk';
    
    protected $fillable = [
        'nama',
        'keterangan'
    ];

    public function produksi(): HasMany
    {
        return $this->hasMany(Produksi::class, 'jenis_produk_id');
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'jenis_produk_id');
    }
}