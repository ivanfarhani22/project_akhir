<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\BulkSchedulesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BulkScheduleImportController extends Controller
{
    public function template()
    {
        $path = base_path('resources/templates/import/bulk_schedules_template.csv');
        return response()->download($path, 'bulk_schedules_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10MB
            'mode' => ['required', 'in:replace,merge'],
        ]);

        // Be permissive for Windows Excel-generated CSV
        $file = $validated['file'];
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['csv', 'xlsx', 'xls'], true)) {
            return back()->withErrors(['file' => 'File harus CSV atau Excel (.xlsx/.xls).']);
        }

        $mode = $validated['mode'];

        $import = new BulkSchedulesImport($mode);

        try {
            Excel::import($import, $file);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }

        if (count($import->failures()) > 0) {
            return back()->with('import_failures', $import->failures());
        }

        return back()->with('success', 'Import jadwal massal berhasil diproses.');
    }
}
