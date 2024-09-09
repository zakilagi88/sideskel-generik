<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function importedBy()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function failedImports(): HasMany
    {
        return $this->hasMany(ImportFailed::class, 'import_id');
    }
}
