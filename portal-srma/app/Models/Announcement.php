<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'attachment',
        'attachment_name',
        'is_important',
        'status',
        'published_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'is_important' => 'boolean',
            'published_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            if (empty($announcement->slug)) {
                $announcement->slug = Str::slug($announcement->title);
            }
            $originalSlug = $announcement->slug;
            $count = 1;
            while (self::where('slug', $announcement->slug)->exists()) {
                $announcement->slug = $originalSlug . '-' . $count++;
            }
        });

        static::updating(function ($announcement) {
            if ($announcement->isDirty('title') && !$announcement->isDirty('slug')) {
                $announcement->slug = Str::slug($announcement->title);
                $originalSlug = $announcement->slug;
                $count = 1;
                while (self::where('slug', $announcement->slug)->where('id', '!=', $announcement->id)->exists()) {
                    $announcement->slug = $originalSlug . '-' . $count++;
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

    public function scopeActive($query)
    {
        return $query->published()
                    ->where(function ($q) {
                        $q->whereNull('expired_at')
                          ->orWhere('expired_at', '>', now());
                    });
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('is_important', 'desc')
                    ->orderBy('published_at', 'desc');
    }

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }
}
