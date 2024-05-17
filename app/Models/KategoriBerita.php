<?php

namespace App\Models;

use App\Models\Web\Berita;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBerita extends Model
{
    use HasFactory;

    protected $table = 'kategori_berita';

    protected $primaryKey = 'kategori_berita_id';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class, 'kategori_berita_id', 'kategori_berita_id');
    }
}