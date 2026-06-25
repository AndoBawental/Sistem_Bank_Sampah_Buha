<?php
// app/Models/Stok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';
    
    protected $fillable = [
        'jenis_plastik_id',
        'total_berat'
    ];

    protected $casts = [
        'total_berat' => 'float'
    ];

    public function jenisPlastik(): BelongsTo
    {
        return $this->belongsTo(JenisPlastik::class, 'jenis_plastik_id');
    }

    public function adjustmentLogs(): HasMany
    {
        return $this->hasMany(StokAdjustmentLog::class, 'stok_id');
    }
}