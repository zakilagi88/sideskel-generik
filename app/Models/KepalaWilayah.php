<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class KepalaWilayah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];
    protected $table = 'kepala_wilayah';
    protected $fillable = [
        'kepala_nik',
        'kepala_id',
        'kepala_type',
    ];

    public function kepala(): MorphTo
    {
        return $this->morphTo();
    }

    public function penduduks(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kepala_nik', 'nik');
    }
}