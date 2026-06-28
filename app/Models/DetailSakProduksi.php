<?php
// app/Models/DetailSakProduksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSakProduksi extends Model
{
    protected $table = 'detail_sak_produksi';
    
    protected $fillable = [
        'detail_hasil_produksi_id',
        'nomor_sak',
        'berat_kg'
    ];

    protected $casts = ['berat_kg' => 'float', 'nomor_sak' => 'integer'];

    public function detailHasilProduksi(): BelongsTo
    {
        return $this->belongsTo(DetailHasilProduksi::class);
    }
}