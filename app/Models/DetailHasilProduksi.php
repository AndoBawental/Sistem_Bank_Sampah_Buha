<?php
// Update app/Models/DetailHasilProduksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailHasilProduksi extends Model
{
    protected $table = 'detail_hasil_produksi';
    
    protected $fillable = [
        'produksi_id',
        'jenis_produk_id',
        'jumlah_sak',
        'total_berat_kg'
    ];

    protected $casts = [
        'jumlah_sak' => 'integer',
        'total_berat_kg' => 'float'
    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class);
    }

    public function jenisProduk(): BelongsTo
    {
        return $this->belongsTo(JenisProduk::class);
    }

    public function sakProduksi(): HasMany
    {
        return $this->hasMany(DetailSakProduksi::class);
    }
}