<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Wilayah extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];
    protected $table = 'wilayah';
    protected $primaryKey = 'wilayah_id';
    protected $fillable = [
        'deskel_id',
        'wilayah_id',
        'wilayah_nama',
        'wilayah_kepala',
        'tingkatan',
        'parent_id',

    ];

    public function deskel(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahan::class, 'deskel_id', 'deskel_id');
    }

    public function kepalaWilayah(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'wilayah_kepala', 'nik');
    }
}
