<?php

namespace App\Observers;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;

/**
 * Activity Log Observer
 * 
 * Observer universal untuk mencatat aktivitas CRUD pada model.
 * Dapat di-attach ke model manapun yang ingin di-log.
 * 
 * Usage:
 * Di AppServiceProvider@boot():
 * News::observe(ActivityLogObserver::class);
 * Announcement::observe(ActivityLogObserver::class);
 */
class ActivityLogObserver
{
    /**
     * Menyimpan original values sebelum update
     */
    protected static array $originalValues = [];

    /**
     * Map model class ke nama Indonesia
     */
    protected array $modelNames = [
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

    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        if (!$this->shouldLog()) {
            return;
        }

        $modelName = $this->getModelName($model);
        $identifier = $this->getIdentifier($model);

        ActivityLogService::logCreate(
            $model,
            "Membuat {$modelName}: {$identifier}"
        );
    }

    /**
     * Handle the Model "updating" event.
     * Simpan original values sebelum update
     */
    public function updating(Model $model): void
    {
        $key = get_class($model) . ':' . $model->getKey();
        self::$originalValues[$key] = $model->getOriginal();
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        if (!$this->shouldLog()) {
            return;
        }

        // Skip jika tidak ada perubahan
        if (!$model->wasChanged()) {
            return;
        }

        $key = get_class($model) . ':' . $model->getKey();
        $oldValues = self::$originalValues[$key] ?? [];
        unset(self::$originalValues[$key]);

        $modelName = $this->getModelName($model);
        $identifier = $this->getIdentifier($model);

        ActivityLogService::logUpdate(
            $model,
            $oldValues,
            "Mengupdate {$modelName}: {$identifier}"
        );
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        if (!$this->shouldLog()) {
            return;
        }

        $modelName = $this->getModelName($model);
        $identifier = $this->getIdentifier($model);

        ActivityLogService::logDelete(
            $model,
            "Menghapus {$modelName}: {$identifier}"
        );
    }

    /**
     * Cek apakah harus log aktivitas
     */
    protected function shouldLog(): bool
    {
        return auth()->check();
    }

    /**
     * Get nama model yang user-friendly
     */
    protected function getModelName(Model $model): string
    {
        $className = class_basename($model);
        return $this->modelNames[$className] ?? strtolower($className);
    }

    /**
     * Get identifier untuk log (title/name/id)
     */
    protected function getIdentifier(Model $model): string
    {
        if (isset($model->title)) {
            return $model->title;
        }
        
        if (isset($model->name)) {
            return $model->name;
        }

        return (string) $model->getKey();
    }
}
