<?php
// app/Models/Produksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksi';
    
    protected $fillable = [
        'tanggal',
        'jenis_produk_id',
        'user_id',
        'keterangan'
    ];

    protected $casts = [
         'tanggal' => 'datetime',
    ];

    public function jenisProduk(): BelongsTo
    {
        return $this->belongsTo(JenisProduk::class, 'jenis_produk_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailBahanProduksi(): HasMany
    {
        return $this->hasMany(DetailBahanProduksi::class, 'produksi_id');
    }

    public function detailHasilProduksi(): HasMany
    {
        return $this->hasMany(DetailHasilProduksi::class, 'produksi_id');
    }
}