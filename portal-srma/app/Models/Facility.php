<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'quantity',
        'condition',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return asset('images/default-facility.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function getConditionLabelAttribute()
    {
        return match($this->condition) {
            'baik' => 'Baik',
            'cukup' => 'Cukup',
            'kurang' => 'Kurang',
            default => $this->condition,
        };
    }
}
