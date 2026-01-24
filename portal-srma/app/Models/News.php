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
}
