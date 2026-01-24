<?php

namespace App\Traits;

use App\Services\ActivityLogService;

/**
 * Trait LogsActivity
 * 
 * Trait untuk auto-logging aktivitas model menggunakan Observer pattern.
 * Cukup tambahkan `use LogsActivity;` di model yang ingin di-log.
 * 
 * Fitur:
 * - Auto log saat CREATE, UPDATE, DELETE
 * - Menyimpan old_values dan new_values
 * - Exclude kolom sensitif
 * 
 * @example
 * class News extends Model {
 *     use LogsActivity;
 * }
 */
trait LogsActivity
{
    /**
     * Boot trait - register model events
     */
    public static function bootLogsActivity(): void
    {
        // Log ketika model dibuat
        static::created(function ($model) {
            if ($model->shouldLogActivity()) {
                ActivityLogService::logCreate(
                    $model,
                    $model->getActivityLogDescription('create')
                );
            }
        });

        // Simpan old values sebelum update
        static::updating(function ($model) {
            $model->oldAttributesForLog = $model->getOriginal();
        });

        // Log ketika model diupdate
        static::updated(function ($model) {
            if ($model->shouldLogActivity() && $model->isDirty()) {
                ActivityLogService::logUpdate(
                    $model,
                    $model->oldAttributesForLog ?? [],
                    $model->getActivityLogDescription('update')
                );
            }
        });

        // Log ketika model dihapus
        static::deleted(function ($model) {
            if ($model->shouldLogActivity()) {
                ActivityLogService::logDelete(
                    $model,
                    $model->getActivityLogDescription('delete')
                );
            }
        });
    }

    /**
     * Cek apakah aktivitas harus di-log
     * Override di model untuk custom logic
     */
    public function shouldLogActivity(): bool
    {
        // Hanya log jika ada user yang login
        return auth()->check();
    }

    /**
     * Get deskripsi untuk activity log
     * Override di model untuk custom description
     */
    public function getActivityLogDescription(string $action): ?string
    {
        $modelName = $this->getActivityLogModelName();
        $identifier = $this->getActivityLogIdentifier();

        return match ($action) {
            'create' => "Membuat {$modelName}: {$identifier}",
            'update' => "Mengupdate {$modelName}: {$identifier}",
            'delete' => "Menghapus {$modelName}: {$identifier}",
            default => null,
        };
    }

    /**
     * Get nama model yang user-friendly
     * Override di model untuk custom name
     */
    public function getActivityLogModelName(): string
    {
        // Map model class ke nama Indonesia
        $modelNames = [
            'News' => 'berita',
            'Announcement' => 'pengumuman',
            'Agenda' => 'agenda',
            'Gallery' => 'galeri',
            'GalleryCategory' => 'kategori galeri',
            'Banner' => 'banner',
            'Profile' => 'profil',
            'Contact' => 'kontak',
            'Setting' => 'pengaturan',
            'User' => 'pengguna',
            'Teacher' => 'guru',
            'Staff' => 'tenaga kependidikan',
            'Facility' => 'fasilitas',
            'StudentData' => 'data siswa',
            'StudentDistribution' => 'persebaran siswa',
        ];

        $className = class_basename($this);
        
        return $modelNames[$className] ?? strtolower($className);
    }

    /**
     * Get identifier untuk log description
     * Biasanya title/name/id dari record
     */
    public function getActivityLogIdentifier(): string
    {
        // Prioritas: title > name > id
        if (isset($this->title)) {
            return $this->title;
        }
        
        if (isset($this->name)) {
            return $this->name;
        }

        return (string) $this->getKey();
    }

    /**
     * Kolom yang di-exclude dari logging
     * Override untuk custom exclusion
     */
    public function getExcludedLogColumns(): array
    {
        return [
            'password',
            'remember_token',
            'updated_at',
        ];
    }
}
