<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class KepalaEntitas extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];
    protected $table = 'kepala_entitas';
    protected $fillable = [
        'kepala_id',
        'kepala_nik',
        'kepala_type',
        'entitas_id',
        'entitas_type',
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
