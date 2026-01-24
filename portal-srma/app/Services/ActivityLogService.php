<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Activity Log Service
 * 
 * Service class untuk mengelola pencatatan log aktivitas secara terpusat.
 * Mengimplementasikan Single Responsibility Principle untuk logging.
 * 
 * @author Portal Sekolah SRMA
 * @version 1.0
 */
class ActivityLogService
{
    /**
     * Kolom yang akan di-exclude dari logging
     * Mencegah data sensitif tercatat di log
     */
    protected static array $excludedColumns = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Catat aktivitas CREATE
     */
    public static function logCreate(Model $model, ?string $customDescription = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = $customDescription ?? "Membuat data {$modelName} baru";

        return self::log(
            action: 'create',
            description: $description,
            modelType: get_class($model),
            modelId: $model->getKey(),
            newValues: self::filterAttributes($model->getAttributes())
        );
    }

    /**
     * Catat aktivitas UPDATE
     */
    public static function logUpdate(Model $model, array $oldValues, ?string $customDescription = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = $customDescription ?? "Mengupdate data {$modelName}";

        // Hanya catat perubahan yang berbeda
        $changes = $model->getChanges();
        $filteredOldValues = self::filterAttributes(
            array_intersect_key($oldValues, $changes)
        );
        $filteredNewValues = self::filterAttributes($changes);

        return self::log(
            action: 'update',
            description: $description,
            modelType: get_class($model),
            modelId: $model->getKey(),
            oldValues: $filteredOldValues,
            newValues: $filteredNewValues
        );
    }

    /**
     * Catat aktivitas DELETE
     */
    public static function logDelete(Model $model, ?string $customDescription = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = $customDescription ?? "Menghapus data {$modelName}";

        return self::log(
            action: 'delete',
            description: $description,
            modelType: get_class($model),
            modelId: $model->getKey(),
            oldValues: self::filterAttributes($model->getAttributes())
        );
    }

    /**
     * Catat aktivitas LOGIN
     */
    public static function logLogin(?string $customDescription = null): ActivityLog
    {
        return self::log(
            action: 'login',
            description: $customDescription ?? 'Admin login berhasil'
        );
    }

    /**
     * Catat aktivitas LOGOUT
     */
    public static function logLogout(?string $customDescription = null): ActivityLog
    {
        return self::log(
            action: 'logout',
            description: $customDescription ?? 'Admin logout'
        );
    }

    /**
     * Method utama untuk mencatat log
     */
    public static function log(
        string $action,
        ?string $description = null,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => Auth::id(),
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

    /**
     * Filter attributes untuk menghilangkan data sensitif
     */
    protected static function filterAttributes(array $attributes): array
    {
        return array_diff_key($attributes, array_flip(self::$excludedColumns));
    }

    /**
     * Hapus log yang lebih lama dari bulan tertentu
     * Digunakan untuk retensi data
     * 
     * @param int $months Jumlah bulan data yang dipertahankan
     * @return int Jumlah record yang dihapus
     */
    public static function deleteOldLogs(int $months = 6): int
    {
        $cutoffDate = now()->subMonths($months);
        
        return ActivityLog::where('created_at', '<', $cutoffDate)->delete();
    }

    /**
     * Get statistik log untuk dashboard
     */
    public static function getStatistics(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        return [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::where('created_at', '>=', $today)->count(),
            'this_month' => ActivityLog::where('created_at', '>=', $thisMonth)->count(),
            'by_action' => ActivityLog::selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action')
                ->toArray(),
        ];
    }
}
