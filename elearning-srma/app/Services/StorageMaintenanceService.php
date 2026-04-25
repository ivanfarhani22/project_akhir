<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageMaintenanceService
{
    /**
     * Convert bytes to a human readable string.
     */
    public static function humanBytes(int|float $bytes, int $precision = 2): string
    {
        $bytes = (float) $bytes;
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) floor(log($bytes, 1024));
        $power = max(0, min($power, count($units) - 1));

        $value = $bytes / (1024 ** $power);
        return number_format($value, $precision) . ' ' . $units[$power];
    }

    /**
     * Return storage usage summary for public disk.
     */
    public static function publicDiskSummary(): array
    {
        $disk = Storage::disk('public');

        $folders = [
            'materials',
            'assignments',
            'submissions',
            'banners',
        ];

        $byFolder = [];
        $totalBytes = 0;
        $totalFiles = 0;

        foreach ($folders as $folder) {
            $result = self::folderSummary($disk, $folder);
            $byFolder[$folder] = $result;
            $totalBytes += $result['bytes'];
            $totalFiles += $result['files'];
        }

        return [
            'disk' => 'public',
            'total' => [
                'bytes' => $totalBytes,
                'files' => $totalFiles,
                'human' => self::humanBytes($totalBytes),
            ],
            'by_folder' => $byFolder,
        ];
    }

    /**
     * List largest files under a folder.
     */
    public static function largestFiles(string $folder, int $limit = 20): array
    {
        $disk = Storage::disk('public');

        $files = [];
        foreach ($disk->allFiles($folder) as $path) {
            try {
                $size = $disk->size($path);
            } catch (\Throwable $e) {
                $size = 0;
            }

            $files[] = [
                'path' => $path,
                'bytes' => (int) $size,
                'human' => self::humanBytes((int) $size),
                'last_modified' => self::safeLastModified($disk, $path),
            ];
        }

        usort($files, fn ($a, $b) => $b['bytes'] <=> $a['bytes']);

        return array_slice($files, 0, max(1, $limit));
    }

    /**
     * Delete a list of file paths from the public disk.
     *
     * @return array{deleted:int,failed:int,failed_paths:array<int,string>,bytes_deleted:int}
     */
    public static function deletePublicFiles(array $paths): array
    {
        $disk = Storage::disk('public');

        $deleted = 0;
        $failed = 0;
        $failedPaths = [];
        $bytesDeleted = 0;

        foreach ($paths as $path) {
            $path = (string) $path;
            $path = ltrim($path, '/');

            // Guard: only allow within known app folders (avoid arbitrary deletions)
            if (!self::isAllowedPublicPath($path)) {
                $failed++;
                $failedPaths[] = $path;
                continue;
            }

            try {
                $size = $disk->exists($path) ? (int) $disk->size($path) : 0;
                if ($disk->delete($path)) {
                    $deleted++;
                    $bytesDeleted += $size;
                } else {
                    $failed++;
                    $failedPaths[] = $path;
                }
            } catch (\Throwable $e) {
                $failed++;
                $failedPaths[] = $path;
            }
        }

        return [
            'deleted' => $deleted,
            'failed' => $failed,
            'failed_paths' => $failedPaths,
            'bytes_deleted' => $bytesDeleted,
        ];
    }

    /**
     * Cleanup old files inside a folder based on last modified time.
     *
     * @return array{deleted:int,failed:int,bytes_deleted:int,considered:int}
     */
    public static function cleanupOldPublicFiles(string $folder, int $days, ?int $keepLatest = null, bool $dryRun = true): array
    {
        $disk = Storage::disk('public');

        $cutoff = now()->subDays(max(0, $days))->timestamp;

        $files = [];
        foreach ($disk->allFiles($folder) as $path) {
            $lm = self::safeLastModified($disk, $path);
            $files[] = [
                'path' => $path,
                'last_modified' => $lm,
                'bytes' => (int) (self::safeSize($disk, $path) ?? 0),
            ];
        }

        // Sort newest first, so keepLatest preserves newest files.
        usort($files, fn ($a, $b) => ($b['last_modified'] ?? 0) <=> ($a['last_modified'] ?? 0));

        $toConsider = $files;
        if ($keepLatest !== null && $keepLatest > 0) {
            $toConsider = array_slice($files, $keepLatest);
        }

        $targets = [];
        foreach ($toConsider as $f) {
            $lm = $f['last_modified'] ?? 0;
            if ($lm > 0 && $lm < $cutoff) {
                $targets[] = $f;
            }
        }

        $considered = count($targets);
        if ($dryRun) {
            return [
                'deleted' => 0,
                'failed' => 0,
                'bytes_deleted' => array_sum(array_column($targets, 'bytes')),
                'considered' => $considered,
            ];
        }

        $result = self::deletePublicFiles(array_column($targets, 'path'));

        return [
            'deleted' => $result['deleted'],
            'failed' => $result['failed'],
            'bytes_deleted' => $result['bytes_deleted'],
            'considered' => $considered,
        ];
    }

    /**
     * Cleanup old files inside a folder based on last modified time.
     *
     * @return array{deleted:int,failed:int,bytes_deleted:int,considered:int,skipped_referenced:int}
     */
    public static function cleanupOldPublicFilesSafe(string $folder, int $days, ?int $keepLatest = null, bool $dryRun = true): array
    {
        $disk = Storage::disk('public');

        $ref = StorageReferenceScanner::referencedPublicPaths();
        $referenced = $ref['referenced'];

        $cutoff = now()->subDays(max(0, $days))->timestamp;

        $files = [];
        foreach ($disk->allFiles($folder) as $path) {
            $lm = self::safeLastModified($disk, $path);
            $files[] = [
                'path' => $path,
                'last_modified' => $lm,
                'bytes' => (int) (self::safeSize($disk, $path) ?? 0),
            ];
        }

        // Sort newest first, so keepLatest preserves newest files.
        usort($files, fn ($a, $b) => ($b['last_modified'] ?? 0) <=> ($a['last_modified'] ?? 0));

        $toConsider = $files;
        if ($keepLatest !== null && $keepLatest > 0) {
            $toConsider = array_slice($files, $keepLatest);
        }

        $targets = [];
        $skippedReferenced = 0;
        foreach ($toConsider as $f) {
            $lm = $f['last_modified'] ?? 0;
            if (!($lm > 0 && $lm < $cutoff)) {
                continue;
            }

            // Safety: never delete a file that is still referenced in DB.
            if (isset($referenced[$f['path']])) {
                $skippedReferenced++;
                continue;
            }

            $targets[] = $f;
        }

        $considered = count($targets);
        if ($dryRun) {
            return [
                'deleted' => 0,
                'failed' => 0,
                'bytes_deleted' => array_sum(array_column($targets, 'bytes')),
                'considered' => $considered,
                'skipped_referenced' => $skippedReferenced,
            ];
        }

        $result = self::deletePublicFiles(array_column($targets, 'path'));

        return [
            'deleted' => $result['deleted'],
            'failed' => $result['failed'],
            'bytes_deleted' => $result['bytes_deleted'],
            'considered' => $considered,
            'skipped_referenced' => $skippedReferenced,
        ];
    }

    private static function folderSummary($disk, string $folder): array
    {
        $files = $disk->allFiles($folder);

        $bytes = 0;
        foreach ($files as $path) {
            $size = self::safeSize($disk, $path);
            if ($size !== null) {
                $bytes += $size;
            }
        }

        $count = count($files);

        return [
            'folder' => $folder,
            'bytes' => $bytes,
            'files' => $count,
            'human' => self::humanBytes($bytes),
        ];
    }

    private static function safeSize($disk, string $path): ?int
    {
        try {
            return (int) $disk->size($path);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private static function safeLastModified($disk, string $path): ?int
    {
        try {
            return (int) $disk->lastModified($path);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private static function isAllowedPublicPath(string $path): bool
    {
        $allowedPrefixes = [
            'materials/',
            'assignments/',
            'submissions/',
            'banners/',
        ];

        foreach ($allowedPrefixes as $prefix) {
            if (Str::startsWith($path, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
