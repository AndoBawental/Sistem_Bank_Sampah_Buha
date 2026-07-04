<?php
// app/Models/DetailBahanProduksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBahanProduksi extends Model
{
    use HasFactory;

    protected $table = 'detail_bahan_produksi';
    
    protected $fillable = [
        'produksi_id',
         'detail_hasil_produksi_id',
        'stok_id',           
        'jenis_plastik_id',
        'berat_kg'           
    ];

    protected $casts = [
        'berat_kg' => 'float' 
    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

     public function detailHasilProduksi(): BelongsTo // ✅ Tambah ini
    {
        return $this->belongsTo(DetailHasilProduksi::class, 'detail_hasil_produksi_id');
    }

    public function jenisPlastik(): BelongsTo
    {
        return $this->belongsTo(JenisPlastik::class, 'jenis_plastik_id');
    }
}