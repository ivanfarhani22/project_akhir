<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\StorageMaintenanceService;
use Illuminate\Support\Facades\Schedule;
use App\Services\StorageReferenceScanner;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('storage:cleanup {folder=submissions : Target folder (materials|assignments|submissions|banners)} {--days=180 : Delete files older than N days} {--keep-latest=0 : Keep newest N files (0 = keep none)} {--force : Actually delete files (default is dry-run)}', function () {
    $folder = (string) $this->argument('folder');
    $days = (int) $this->option('days');
    $keepLatest = (int) $this->option('keep-latest');
    $force = (bool) $this->option('force');

    if (!in_array($folder, ['materials', 'assignments', 'submissions', 'banners'], true)) {
        $this->error('Invalid folder. Use: materials|assignments|submissions|banners');
        return 1;
    }

    $dryRun = !$force;

    $result = StorageMaintenanceService::cleanupOldPublicFilesSafe(
        $folder,
        max(1, $days),
        $keepLatest > 0 ? $keepLatest : null,
        $dryRun
    );

    if ($dryRun) {
        $this->info(sprintf(
            '[DRY-RUN][SAFE] folder=%s days=%d keepLatest=%s considered=%d skippedReferenced=%d bytes=%s',
            $folder,
            $days,
            $keepLatest > 0 ? (string) $keepLatest : '-',
            $result['considered'],
            $result['skipped_referenced'] ?? 0,
            StorageMaintenanceService::humanBytes($result['bytes_deleted'])
        ));

        $this->warn('Add --force to actually delete files.');
        return 0;
    }

    $this->info(sprintf(
        'Done. folder=%s deleted=%d failed=%d skippedReferenced=%d bytes=%s considered=%d',
        $folder,
        $result['deleted'],
        $result['failed'],
        $result['skipped_referenced'] ?? 0,
        StorageMaintenanceService::humanBytes($result['bytes_deleted']),
        $result['considered']
    ));

    return $result['failed'] > 0 ? 2 : 0;
})->purpose('Cleanup old files on public storage (safe by folder + dry-run by default)');

Artisan::command('storage:scan-references {--orphans-limit=500 : Maximum orphan file paths to list}', function () {
    $limit = (int) $this->option('orphans-limit');
    $limit = max(0, min(5000, $limit));

    $report = StorageReferenceScanner::scanPublicDisk(['materials', 'assignments', 'submissions', 'banners'], $limit);

    $this->info('=== Storage Reference Scan ===');
    $this->line('Referenced unique paths : ' . ($report['stats']['referenced_unique'] ?? 0));
    $this->line('Missing referenced files: ' . ($report['stats']['missing_count'] ?? 0));
    $this->line('Orphan files found      : ' . ($report['stats']['orphans_count'] ?? 0) . ' (limit ' . ($report['stats']['orphans_limit'] ?? $limit) . ')');

    if (!empty($report['missing'])) {
        $this->warn('--- Missing referenced files (DB points to file that does not exist) ---');
        foreach ($report['missing'] as $m) {
            $this->line(sprintf('[%s] %s', $m['type'] ?? 'unknown', $m['disk_path'] ?? '-'));
        }
    }

    if (!empty($report['orphans'])) {
        $this->warn('--- Orphan files (exist in disk but not referenced by DB) ---');
        foreach ($report['orphans'] as $p) {
            $this->line($p);
        }
    }

    $this->comment('Tip: Jalankan command ini setelah cleanup/bulk delete untuk memastikan tidak ada broken reference.');
})->purpose('Scan broken storage references (DB->missing file) and orphan files.');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks - Storage Cleanup (SAFE)
|--------------------------------------------------------------------------
|
| Cleanup otomatis untuk mencegah storage penuh.
| Mode AMAN: hanya menghapus file yang sudah tidak direferensikan database.
|
*/
Schedule::command('storage:cleanup submissions --days=180 --keep-latest=0 --force')
    ->daily()
    ->at('02:30')
    ->withoutOverlapping()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/storage-cleanup.log'))
    ->description('Cleanup old submission files (SAFE: skip referenced).');
