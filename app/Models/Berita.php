<?php

namespace App\Models;

use App\Enum\Website\Status_Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Tags\HasTags;

class Berita extends Model
{
    use HasFactory;
    use HasTags;

    protected $table = 'berita';

    protected $primaryKey = 'berita_id';

    protected $guarded = ['berita_id'];

    protected $fillable = [
        'title',
        'body',
        'slug',
        'meta_description',
        'published_at',
        'status',
        'user_id',
        'kategori+berita_id',
        'featured_image_url',
        'scheduled_for',

    ];

    protected $casts = [
        'featured_image_url' => 'array',
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'status' => Status_Post::class
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', Status_Post::PUBLISH);
    }
}