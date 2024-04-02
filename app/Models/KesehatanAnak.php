<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KesehatanAnak extends Model
{
    use HasFactory;

    protected $table = 'kesehatan_anaks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'anak_id',
        'ibu_id',
        'berat_badan',
        'tinggi_badan',
        'imt',
        'kategori_tbu',
        'z_score_tbu',
        'kategori_bbu',
        'z_score_bbu',
        'kategori_imtu',
        'z_score_imtu',
        'kategori_tb_bb',
        'z_score_tb_bb',
    ];

    public function anak(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'anak_id', 'nik')
            ->whereDate('tanggal_lahir', '>', now()->subYears(5));
    }

    public function ibu(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'ibu_id', 'nik');
    }
}
