<?php

namespace App\Models\Web;

use App\Enums\Desa\StatusBeritaType;
use App\Models\Web\KategoriBerita;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        'kategori_berita_id',
        'gambar',
        'scheduled_for',

    ];

    protected $casts = [
        'gambar' => 'array',
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'status' => StatusBeritaType::class
    ];

    public function getLinkLabel(): string
    {
        return $this->title;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class, 'kategori_berita_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', StatusBeritaType::PUBLISH);
    }

    public function scopeKategoriBerita($query, $kategori)
    {
        return $query->whereHas('kategori', function ($query) use ($kategori) {
            $query->where('slug', $kategori);
        });
    }

    public function getExcerpt(): string
    {
        return Str::limit(strip_tags($this->body), 200);
    }

    public function getReadingTime(): string
    {
        $word = str_word_count(strip_tags($this->body));
        $m = floor($word / 250);
        $s = floor($word % 250 / (250 / 60));

        if ($m == 0) {
            return 'kurang dari 1 menit';
        } else {
            return $m . ' menit ' . $s . ' detik';
        }
    }

    public function getThumbnail(): string
    {
        return $this->gambar ? Storage::url($this->gambar) : 'https://ui-avatars.com/api/?name=' . urlencode($this->title) . '&background=random&size=512';
    }
}
