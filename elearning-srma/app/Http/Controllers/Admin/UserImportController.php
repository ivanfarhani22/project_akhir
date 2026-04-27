<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserImportController extends Controller
{
    public function template(): BinaryFileResponse
    {
        $path = base_path('resources/templates/import/users_template.csv');

        return response()->download(
            $path,
            'template-import-users.csv',
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                // Some CSV files are detected as text/plain or application/vnd.ms-excel on Windows/Excel
                'mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/octet-stream',
                'max:10240',
            ],
        ], [
            'file.required' => 'Silakan pilih file untuk diimport.',
            'file.mimetypes' => 'File harus CSV atau Excel (xlsx/xls). Jika CSV ditolak, pastikan ekstensi file .csv dan gunakan delimiter koma (,).',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $import = new UsersImport();
        Excel::import($import, $request->file('file'));

        $failures = method_exists($import, 'failures') ? $import->failures() : collect();

        if ($failures && $failures->count() > 0) {
            return back()
                ->with('error', 'Import selesai, tetapi ada baris yang gagal. Silakan cek detail error di bawah.')
                ->with('import_failures', $failures);
        }

        return back()->with('success', 'Import pengguna berhasil.');
    }
}
