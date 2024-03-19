<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Bantuanable extends Model
{
    use HasFactory;

    protected $table = 'bantuanables';

    protected $fillable = [
        'bantuan_id',
        'bantuanable_id',
        'bantuanable_type',
    ];

    public function bantuanable(): MorphTo
    {
        return $this->morphTo();
    }
}
