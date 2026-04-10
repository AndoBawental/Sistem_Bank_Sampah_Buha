<?php
// app/Models/JenisPlastik.php

namespace App\Models;
use App\Models\DetailBahanProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPlastik extends Model
{
    use HasFactory;

    protected $table = 'jenis_plastik';
    
    protected $fillable = [
        'nama',
        'keterangan'
    ];

    public function detailPenerimaanStok(): HasMany
    {
        return $this->hasMany(DetailPenerimaanStok::class, 'jenis_plastik_id');
    }

    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class, 'jenis_plastik_id');
    }

    public function detailBahanProduksi(): HasMany
    {
        return $this->hasMany(DetailBahanProduksi::class, 'jenis_plastik_id');
    }
}