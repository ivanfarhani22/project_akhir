<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'video_url',
        'status',
        'published_at',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            // Ensure unique slug
            $originalSlug = $news->slug;
            $count = 1;
            while (self::where('slug', $news->slug)->exists()) {
                $news->slug = $originalSlug . '-' . $count++;
            }
        });

        static::updating(function ($news) {
            if ($news->isDirty('title') && !$news->isDirty('slug')) {
                $news->slug = Str::slug($news->title);
                $originalSlug = $news->slug;
                $count = 1;
                while (self::where('slug', $news->slug)->where('id', '!=', $news->id)->exists()) {
                    $news->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/default-news.jpg');
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        $url = trim((string) $this->video_url);
        if ($url === '') {
            return null;
        }

        // YouTube
        // - https://www.youtube.com/watch?v=VIDEOID
        // - https://youtu.be/VIDEOID
        // - https://www.youtube.com/embed/VIDEOID
        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $query = parse_url($url, PHP_URL_QUERY) ?: '';

        $host = strtolower($host);

        if (str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be')) {
            $videoId = null;

            if (str_contains($host, 'youtu.be')) {
                $videoId = ltrim($path, '/');
            } elseif (str_starts_with($path, '/embed/')) {
                $videoId = basename($path);
            } else {
                parse_str($query, $parts);
                $videoId = $parts['v'] ?? null;
            }

            if ($videoId) {
                $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', $videoId);
                return 'https://www.youtube.com/embed/' . $videoId;
            }
        }

        // Google Drive
        // - https://drive.google.com/file/d/FILE_ID/view?usp=sharing
        // - https://drive.google.com/open?id=FILE_ID
        if (str_contains($host, 'drive.google.com')) {
            if (preg_match('~^/file/d/([^/]+)~', $path, $m)) {
                return 'https://drive.google.com/file/d/' . $m[1] . '/preview';
            }

            parse_str($query, $parts);
            if (!empty($parts['id'])) {
                return 'https://drive.google.com/file/d/' . $parts['id'] . '/preview';
            }
        }

        // Otherwise: return original URL (might already be an embeddable URL)
        return $url;
    }
}
