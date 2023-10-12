<?php

namespace App\Models;

use App\Enum\Website\Status_Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Tags\HasTags;

class Article extends Model
{
    use HasFactory;
    use HasTags;

    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'body',
        'slug',
        'meta_description',
        'published_at',
        'status',
        'user_id',
        'category_id',
        'featured_image_url',
        'scheduled_for',

    ];

    protected $casts = [
        'featured_image_url' => 'array', // 'array' or 'json
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'status' => Status_Post::class
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}