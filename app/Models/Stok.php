<?php

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

    /**
     * Update stok untuk jenis plastik tertentu secara dinamis.
     * * @param int $jenisPlastikId
     * @param float $berat
     * @param bool $isTambah true untuk tambah stok (hasil sortir), false untuk kurangi (produksi)
     * @return void
     */
    public static function updateStok($jenisPlastikId, $berat, $isTambah = true)
    {
        // Mencari data stok, jika belum ada untuk jenis_plastik_id tersebut, otomatis buat baru dengan berat 0
        $stok = self::firstOrCreate(
            ['jenis_plastik_id' => $jenisPlastikId],
            ['total_berat' => 0]
        );

        if ($isTambah) {
            $stok->total_berat += $berat;
        } else {
            // max() mencegah nilai stok menjadi negatif jika $berat pengurang lebih besar dari stok saat ini
            $stok->total_berat = max(0, $stok->total_berat - $berat);
        }

        $stok->save();
    }
}