<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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