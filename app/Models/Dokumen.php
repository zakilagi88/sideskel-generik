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
        'dokumen_jenis',
        'dokumen_nama',
        'dokumen_path',
        'dokumen_file',
    ];


    public function dokumenable(): MorphTo
    {
        return $this->morphTo();
    }
}