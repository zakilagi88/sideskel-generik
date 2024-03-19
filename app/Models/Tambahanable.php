<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tambahanable extends Model
{
    use HasFactory;

    protected $table = 'tambahanables';

    protected $fillable = [
        'tambahan_id',
        'tambahanable_id',
        'tambahanable_type',
    ];

    public function tambahanable(): MorphTo
    {
        return $this->morphTo();
    }
}
