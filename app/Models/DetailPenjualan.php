<?php
// app/Models/DetailPenjualan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    
    protected $fillable = [
        'penjualan_id',
        'jenis_produk_id',
        'jumlah_sak',
        'berat_kirim_kg',
        'potongan_persen',
        'berat_potongan_kg',
        'berat_nett_kg',
        'harga_per_kg',
        'subtotal',
        'detail_sak', // Tambahkan ini
    ];

    protected $casts = [
        'jumlah_sak' => 'integer',
        'berat_kirim_kg' => 'float',
        'potongan_persen' => 'float',
        'berat_potongan_kg' => 'float',
        'berat_nett_kg' => 'float',
        'harga_per_kg' => 'float',
        'subtotal' => 'float',
        'detail_sak' => 'array', // Auto decode JSON
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function jenisProduk(): BelongsTo
    {
        return $this->belongsTo(JenisProduk::class, 'jenis_produk_id');
    }
}