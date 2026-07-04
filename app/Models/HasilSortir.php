<?php
// app/Models/HasilSortir.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilSortir extends Model
{
    use HasFactory;

    protected $table = 'hasil_sortir';
    
    protected $fillable = [
        'penerimaan_id',
        'jenis_plastik_id',    // Jadi nullable karena detail ada di JSON
        'berat_bersih_kg',     // Total berat bersih
        'catatan',
        'detail_sortir',       // JSON detail per jenis
    ];

    protected $casts = [
        'berat_bersih_kg' => 'float',
        'detail_sortir' => 'array',  // Auto decode JSON
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