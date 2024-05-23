<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Traits\Dumpable;
use Staudenmeir\EloquentHasManyDeep\HasTableAlias;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Znck\Eloquent\Traits\BelongsToThrough;

class Wilayah extends Model
{
    use HasFactory, Dumpable;
    use HasRecursiveRelationships;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use HasTableAlias;
    use BelongsToThrough;


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

    public function descendantsKks()
    {
        return $this->hasManyDeepFromRelations(
            $this->descendants(),
            (new static)->penduduks()
        );
    }

    public function kks(): HasMany
    {
        return $this->hasMany(KartuKeluarga::class, 'wilayah_id', 'wilayah_id');
    }

    public function penduduks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Penduduk::class,
            KartuKeluarga::class,
            'wilayah_id', // Foreign key on kk table...
            'kk_id', // Foreign key on penduduk table...
            'wilayah_id', // Local key on wilayah table...
            'kk_id' // Local key on kk table...
        );
    }
}