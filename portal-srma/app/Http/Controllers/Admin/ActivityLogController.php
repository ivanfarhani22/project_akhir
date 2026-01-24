<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Activity Log Controller
 * 
 * Controller untuk mengelola tampilan dan export activity logs.
 * 
 * Fitur:
 * - View logs dengan filter & pagination
 * - Export ke CSV/Excel
 * - Statistik aktivitas
 * 
 * Security:
 * - Hanya admin yang dapat mengakses
 * - Tidak ada fitur edit/delete manual
 */
class ActivityLogController extends Controller
{
    /**
     * Display activity logs dengan filter & search
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();
        
        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', 'like', '%' . $request->model_type . '%');
        }
        
        // Filter by date (single date)
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        // Per page (default 10)
        $perPage = $request->get('per_page', 10);
        
        // Handle "all" option
        if ($perPage === 'all') {
            $logs = $query->get();
            // Wrap in a custom paginator-like object for consistency
            $logs = new \Illuminate\Pagination\LengthAwarePaginator(
                $logs,
                $logs->count(),
                $logs->count() ?: 1,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $logs = $query->paginate((int) $perPage)->withQueryString();
        }
        
        // Get statistics untuk dashboard info
        $statistics = ActivityLogService::getStatistics();
        
        return view('admin.activity-logs.index', compact('logs', 'perPage', 'statistics'));
    }

    /**
     * Export activity logs ke CSV
     */
    public function export(Request $request): StreamedResponse
    {
        $query = ActivityLog::with('user')->latest();
        
        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model_type')) {
            $query->where('model_type', 'like', '%' . $request->model_type . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->get();

        // Generate filename dengan timestamp
        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        // Log export activity
        ActivityLogService::log(
            'export',
            'Export log aktivitas (' . $logs->count() . ' records)',
            ActivityLog::class
        );

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            
            // BOM untuk Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV
            fputcsv($handle, [
                'No',
                'Tanggal',
                'Waktu',
                'User',
                'Aksi',
                'Tipe',
                'Deskripsi',
                'IP Address',
                'User Agent',
            ]);
            
            // Data rows
            foreach ($logs as $index => $log) {
                fputcsv($handle, [
                    $index + 1,
                    $log->created_at->format('d/m/Y'),
                    $log->created_at->format('H:i:s'),
                    $log->user?->name ?? 'System',
                    ucfirst($log->action),
                    $log->model_type ? class_basename($log->model_type) : '-',
                    $log->description ?? '-',
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-',
                ]);
            }
            
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export ke Excel (XLSX format via CSV untuk simplicity)
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        // Gunakan export CSV dengan nama .xlsx
        // Browser akan handle konversi atau user buka dengan Excel
        $query = ActivityLog::with('user')->latest();
        
        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model_type')) {
            $query->where('model_type', 'like', '%' . $request->model_type . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->get();
        
        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.xlsx';

        // Log export activity
        ActivityLogService::log(
            'export',
            'Export log aktivitas ke Excel (' . $logs->count() . ' records)',
            ActivityLog::class
        );

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            
            // BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header dengan tab separator (better for Excel)
            fputcsv($handle, [
                'No',
                'Tanggal',
                'Waktu', 
                'User',
                'Aksi',
                'Tipe',
                'Deskripsi',
                'IP Address',
                'User Agent',
                'Old Values',
                'New Values',
            ], "\t");
            
            foreach ($logs as $index => $log) {
                fputcsv($handle, [
                    $index + 1,
                    $log->created_at->format('d/m/Y'),
                    $log->created_at->format('H:i:s'),
                    $log->user?->name ?? 'System',
                    ucfirst($log->action),
                    $log->model_type ? class_basename($log->model_type) : '-',
                    $log->description ?? '-',
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-',
                    $log->old_values ? json_encode($log->old_values, JSON_UNESCAPED_UNICODE) : '-',
                    $log->new_values ? json_encode($log->new_values, JSON_UNESCAPED_UNICODE) : '-',
                ], "\t");
            }
            
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Show detail log (untuk modal/popup)
     */
    public function show(ActivityLog $log)
    {
        $log->load('user');
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $log->id,
                'user' => $log->user?->name ?? 'System',
                'action' => ucfirst($log->action),
                'model_type' => $log->model_type ? class_basename($log->model_type) : null,
                'model_id' => $log->model_id,
                'description' => $log->description,
                'old_values' => $log->old_values,
                'new_values' => $log->new_values,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'created_at' => $log->created_at->format('d M Y H:i:s'),
            ],
        ]);
    }
}
