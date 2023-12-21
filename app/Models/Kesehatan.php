<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kesehatan extends Model
{
    use HasFactory;

    protected $table = 'kesehatan';

    protected $primaryKey = 'kes_id';

    protected $fillable = [
        'kes_id',
        'kes_cacat_mental_fisik',
        'kes_penyakit_menahun',
        'kes_penyakit_lain',
        'kes_akseptor_kb',

    ];

    protected $casts = [];

    public function penduduks(): BelongsToMany
    {
        return $this->belongsToMany(Penduduk::class, 'penduduk_kesehatan', 'kesehatan_id', 'nik');
    }
}
