<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportFailed extends Model
{
    use HasFactory;

    protected $table = 'failed_imports';

    protected $guarded = [];

    public function importedFile()
    {
        return $this->belongsTo(Import::class, 'import_id');
    }

  
}
