<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nip',
        'position',
        'subject',
        'photo',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        return asset('images/default-avatar.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }
}
