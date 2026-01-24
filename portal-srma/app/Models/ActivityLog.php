<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Activity Log Model
 * 
 * Model untuk menyimpan audit trail aktivitas sistem.
 * Mencatat semua perubahan data penting untuk keamanan dan compliance.
 * 
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $description
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'activity_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Daftar action yang valid
     */
    public const ACTIONS = [
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
        'login' => 'Login',
        'logout' => 'Logout',
        'export' => 'Export',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get user yang melakukan aktivitas
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get model yang terkait dengan log ini (polymorphic-like)
     */
    public function subject(): ?Model
    {
        if (!$this->model_type || !$this->model_id) {
            return null;
        }

        if (!class_exists($this->model_type)) {
            return null;
        }

        return $this->model_type::find($this->model_id);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope untuk filter berdasarkan action
     */
    public function scopeAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk filter berdasarkan model type
     */
    public function scopeForModel(Builder $query, string $modelClass): Builder
    {
        return $query->where('model_type', $modelClass);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeDateRange(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope untuk filter hari ini
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope untuk filter minggu ini
     */
    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    /**
     * Scope untuk filter bulan ini
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods (Legacy Support)
    |--------------------------------------------------------------------------
    */

    /**
     * Helper method untuk quick logging (backward compatible)
     * 
     * @deprecated Use ActivityLogService::log() instead
     */
    public static function log(
        string $action,
        ?string $description = null,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get formatted action name
     */
    public function getActionLabelAttribute(): string
    {
        return self::ACTIONS[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Get model name (human readable)
     */
    public function getModelNameAttribute(): ?string
    {
        if (!$this->model_type) {
            return null;
        }

        $modelNames = [
            'App\\Models\\News' => 'Berita',
            'App\\Models\\Announcement' => 'Pengumuman',
            'App\\Models\\Agenda' => 'Agenda',
            'App\\Models\\Gallery' => 'Galeri',
            'App\\Models\\GalleryCategory' => 'Kategori Galeri',
            'App\\Models\\Banner' => 'Banner',
            'App\\Models\\Profile' => 'Profil',
            'App\\Models\\Contact' => 'Kontak',
            'App\\Models\\Setting' => 'Pengaturan',
            'App\\Models\\User' => 'Pengguna',
            'App\\Models\\Teacher' => 'Guru',
            'App\\Models\\Staff' => 'Tenaga Kependidikan',
            'App\\Models\\Facility' => 'Fasilitas',
            'App\\Models\\StudentData' => 'Data Siswa',
            'App\\Models\\StudentDistribution' => 'Persebaran Siswa',
        ];

        return $modelNames[$this->model_type] ?? class_basename($this->model_type);
    }

    /**
     * Get action color class for UI
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'create' => 'bg-green-100 text-green-800',
            'update' => 'bg-blue-100 text-blue-800',
            'delete' => 'bg-red-100 text-red-800',
            'login' => 'bg-purple-100 text-purple-800',
            'logout' => 'bg-gray-100 text-gray-800',
            'export' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
