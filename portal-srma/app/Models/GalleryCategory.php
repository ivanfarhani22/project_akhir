<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GalleryCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            $originalSlug = $category->slug;
            $count = 1;
            while (self::where('slug', $category->slug)->exists()) {
                $category->slug = $originalSlug . '-' . $count++;
            }
        });
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }
}
