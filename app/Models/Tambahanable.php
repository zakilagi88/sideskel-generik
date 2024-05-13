<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Traits\Dumpable;

class Tambahanable extends Model
{
    use HasFactory, Dumpable;

    protected $table = 'tambahanables';

    protected $primaryKey = 'tambahan_id';

    protected $fillable = [
        'id',
        'tambahanable_id',
        'tambahanable_type',
        'tambahanable_ket',
    ];

    public function tambahanable(): MorphTo
    {
        return $this->morphTo();
    }

    public function tambahan(): BelongsTo
    {
        return $this->belongsTo(Tambahan::class, 'tambahan_id');
    }
}
