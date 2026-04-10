<?php
// app/Models/DetailPenerimaanStok.php

namespace App\Models;
use App\Models\Penerimaan;
use App\Models\JenisPlastik;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenerimaanStok extends Model
{
    use HasFactory;

    protected $table = 'detail_penerimaan_stok';
    
    protected $fillable = [
        'penerimaan_id',
        'jenis_plastik_id',
        'berat',
        'harga'
    ];

    protected $casts = [
        'berat' => 'float',
        'harga' => 'decimal:2'
    ];

    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }

    public function jenisPlastik(): BelongsTo
    {
        return $this->belongsTo(JenisPlastik::class, 'jenis_plastik_id');
    }
}