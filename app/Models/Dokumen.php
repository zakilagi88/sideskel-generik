<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumens';

    protected $fillable = [
        'dokumentable_id',
        'dokumentable_type',
        'dok_nama',
        'dok_jenis',
        'dok_file',
        'dok_path'
    ];


    public function dokumenable(): MorphTo
    {
        return $this->morphTo();
    }
}
