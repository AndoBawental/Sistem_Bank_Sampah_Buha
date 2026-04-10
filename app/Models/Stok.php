<?php
// app/Models/Stok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\JenisPlastik;
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

    // Update stok berdasarkan jenis plastik
    public static function updateStok($jenisPlastikId, $berat, $isAddition = true)
    {
        $stok = self::where('jenis_plastik_id', $jenisPlastikId)->first();
        
        if ($stok) {
            if ($isAddition) {
                $stok->total_berat += $berat;
            } else {
                $stok->total_berat -= $berat;
            }
            $stok->save();
        } else {
            self::create([
                'jenis_plastik_id' => $jenisPlastikId,
                'total_berat' => $isAddition ? $berat : 0
            ]);
        }
        
        return $stok;
    }
}