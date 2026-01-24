<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'location',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($agenda) {
            if (empty($agenda->slug)) {
                $agenda->slug = Str::slug($agenda->title);
            }
            $originalSlug = $agenda->slug;
            $count = 1;
            while (self::where('slug', $agenda->slug)->exists()) {
                $agenda->slug = $originalSlug . '-' . $count++;
            }
        });

        static::updating(function ($agenda) {
            if ($agenda->isDirty('title') && !$agenda->isDirty('slug')) {
                $agenda->slug = Str::slug($agenda->title);
                $originalSlug = $agenda->slug;
                $count = 1;
                while (self::where('slug', $agenda->slug)->where('id', '!=', $agenda->id)->exists()) {
                    $agenda->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString())
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('start_date', 'asc');
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now()->toDateString())
                    ->where(function ($q) {
                        $q->where('end_date', '>=', now()->toDateString())
                          ->orWhereNull('end_date');
                    })
                    ->where('status', 'ongoing');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('start_date', now()->month)
                    ->whereYear('start_date', now()->year);
    }

    public function getFormattedDateAttribute(): string
    {
        $start = $this->start_date->translatedFormat('d F Y');
        if ($this->end_date && $this->end_date->ne($this->start_date)) {
            $end = $this->end_date->translatedFormat('d F Y');
            return "{$start} - {$end}";
        }
        return $start;
    }

    public function getFormattedTimeAttribute(): ?string
    {
        if (!$this->start_time) {
            return null;
        }
        
        $start = Carbon::parse($this->start_time)->format('H:i');
        if ($this->end_time) {
            $end = Carbon::parse($this->end_time)->format('H:i');
            return "{$start} - {$end} WIB";
        }
        return "{$start} WIB";
    }

    public function updateStatus(): void
    {
        $today = now()->toDateString();
        
        if ($this->status === 'cancelled') {
            return;
        }

        if ($this->end_date && $this->end_date->lt($today)) {
            $this->update(['status' => 'completed']);
        } elseif ($this->start_date->lte($today) && (!$this->end_date || $this->end_date->gte($today))) {
            $this->update(['status' => 'ongoing']);
        } elseif ($this->start_date->gt($today)) {
            $this->update(['status' => 'upcoming']);
        }
    }
}
