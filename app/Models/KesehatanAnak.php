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
        'kategori_id',
        'subkategori_id',
        'anak_id',
        'ibu_id',
        'berat_lahir',
        'tinggi_lahir',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriStunting::class, 'kategori_id', 'id');
    }

    public function subkategori(): BelongsTo
    {
        return $this->belongsTo(SubkategoriStunting::class, 'subkategori_id', 'id');
    }

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
