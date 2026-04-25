<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StorageMaintenanceService;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    /**
     * Storage dashboard (summary + largest files + cleanup action).
     */
    public function index(Request $request)
    {
        $summary = StorageMaintenanceService::publicDiskSummary();

        $largestFolder = $request->query('largest_folder', 'submissions');
        $largestLimit = (int) $request->query('largest_limit', 20);
        $largestLimit = max(5, min(100, $largestLimit));

        $largestFiles = StorageMaintenanceService::largestFiles($largestFolder, $largestLimit);

        return view('admin.storage.index', compact('summary', 'largestFolder', 'largestLimit', 'largestFiles'));
    }

    /**
     * Bulk delete selected public files.
     */
    public function delete(Request $request)
    {
        $validated = $request->validate([
            'paths' => ['required', 'array', 'min:1'],
            'paths.*' => ['string'],
        ]);

        $result = StorageMaintenanceService::deletePublicFiles($validated['paths']);

        return back()->with('success', sprintf(
            'Hapus file selesai. Terhapus: %d, Gagal: %d, Ruang dibebaskan: %s',
            $result['deleted'],
            $result['failed'],
            StorageMaintenanceService::humanBytes($result['bytes_deleted'])
        ));
    }

    /**
     * Cleanup old files in a folder.
     */
    public function cleanup(Request $request)
    {
        $validated = $request->validate([
            'folder' => ['required', 'in:materials,assignments,submissions,banners'],
            'days' => ['required', 'integer', 'min:1', 'max:3650'],
            'keep_latest' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'dry_run' => ['nullable', 'boolean'],
        ]);

        $dryRun = (bool) ($validated['dry_run'] ?? false);
        $keepLatest = array_key_exists('keep_latest', $validated) ? $validated['keep_latest'] : null;

        // SAFE cleanup: never delete files still referenced by DB
        $result = StorageMaintenanceService::cleanupOldPublicFilesSafe(
            $validated['folder'],
            (int) $validated['days'],
            $keepLatest === null ? null : (int) $keepLatest,
            $dryRun
        );

        $message = $dryRun
            ? sprintf(
                'Simulasi cleanup (AMAN): dipertimbangkan %d file, diskip karena masih direferensi DB: %d, potensi ruang dibebaskan: %s',
                $result['considered'],
                $result['skipped_referenced'] ?? 0,
                StorageMaintenanceService::humanBytes($result['bytes_deleted'])
            )
            : sprintf(
                'Cleanup (AMAN) selesai. Terhapus: %d, Gagal: %d, Diskip (referensi DB): %d, Ruang dibebaskan: %s',
                $result['deleted'],
                $result['failed'],
                $result['skipped_referenced'] ?? 0,
                StorageMaintenanceService::humanBytes($result['bytes_deleted'])
            );

        return back()->with('success', $message);
    }
}
