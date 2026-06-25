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
        'penerimaan_id',    // NULLABLE - tidak wajib terikat penerimaan
        'jenis_plastik_id',
        'berat_bersih_kg',
        'catatan'
    ];

    protected $casts = [
        'berat_bersih_kg' => 'float'
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