<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Material;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class StorageReferenceScanner
{
    /**
     * Normalizes db path to disk-relative public path.
     * Examples:
     * - storage/materials/x.pdf -> materials/x.pdf
     * - /storage/materials/x.pdf -> materials/x.pdf
     * - materials/x.pdf -> materials/x.pdf
     */
    public static function normalizePublicPath(?string $path): ?string
    {
        $path = trim((string) ($path ?? ''));
        if ($path === '') {
            return null;
        }

        $path = preg_replace('#^https?://[^/]+/#i', '', $path);
        $path = ltrim($path, '/');
        $path = preg_replace('#^storage/#', '', $path);

        return $path !== '' ? $path : null;
    }

    /**
     * Build a set of all referenced public-disk paths from DB.
     *
     * @return array{referenced:array<string,bool>, counts:array<string,int>}
     */
    public static function referencedPublicPaths(): array
    {
        $referenced = [];
        $counts = [
            'materials' => 0,
            'assignments' => 0,
            'submissions' => 0,
        ];

        Material::query()->select('file_path')->whereNotNull('file_path')->chunk(500, function ($rows) use (&$referenced, &$counts) {
            foreach ($rows as $row) {
                $p = self::normalizePublicPath($row->file_path);
                if ($p) {
                    $referenced[$p] = true;
                    $counts['materials']++;
                }
            }
        });

        Assignment::query()->select('file_path')->whereNotNull('file_path')->chunk(500, function ($rows) use (&$referenced, &$counts) {
            foreach ($rows as $row) {
                $p = self::normalizePublicPath($row->file_path);
                if ($p) {
                    $referenced[$p] = true;
                    $counts['assignments']++;
                }
            }
        });

        Submission::query()->select('file_path')->whereNotNull('file_path')->chunk(500, function ($rows) use (&$referenced, &$counts) {
            foreach ($rows as $row) {
                $p = self::normalizePublicPath($row->file_path);
                if ($p) {
                    $referenced[$p] = true;
                    $counts['submissions']++;
                }
            }
        });

        return ['referenced' => $referenced, 'counts' => $counts];
    }

    /**
     * Scan for DB->file missing references and storage orphans.
     *
     * @return array{
     *   missing: array<int,array{type:string, disk_path:string, db_path:string}>,
     *   orphans: array<int,string>,
     *   stats: array<string,mixed>
     * }
     */
    public static function scanPublicDisk(array $folders = ['materials', 'assignments', 'submissions', 'banners'], int $orphansLimit = 500): array
    {
        $disk = Storage::disk('public');

        $ref = self::referencedPublicPaths();
        $referenced = $ref['referenced'];

        $missing = [];

        // Missing references from DB
        foreach (array_keys($referenced) as $diskPath) {
            if (!$disk->exists($diskPath)) {
                $missing[] = [
                    'type' => self::inferType($diskPath),
                    'disk_path' => $diskPath,
                    'db_path' => 'storage/' . $diskPath,
                ];
            }
        }

        // Orphans in storage (best-effort; limited)
        $orphans = [];
        foreach ($folders as $folder) {
            foreach ($disk->allFiles($folder) as $p) {
                if (!isset($referenced[$p])) {
                    $orphans[] = $p;
                    if (count($orphans) >= $orphansLimit) {
                        break 2;
                    }
                }
            }
        }

        return [
            'missing' => $missing,
            'orphans' => $orphans,
            'stats' => [
                'referenced_counts' => $ref['counts'],
                'referenced_unique' => count($referenced),
                'missing_count' => count($missing),
                'orphans_count' => count($orphans),
                'orphans_limit' => $orphansLimit,
            ],
        ];
    }

    private static function inferType(string $diskPath): string
    {
        if (str_starts_with($diskPath, 'materials/')) return 'material';
        if (str_starts_with($diskPath, 'assignments/')) return 'assignment';
        if (str_starts_with($diskPath, 'submissions/')) return 'submission';
        if (str_starts_with($diskPath, 'banners/')) return 'banner';
        return 'unknown';
    }
}
