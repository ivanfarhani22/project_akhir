<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\Material;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of all materials (admin view - all classes).
     */
    public function index()
    {
        $classId = request('class');
        $search = request('search');
        
        $query = Material::with(['eClass', 'uploadedBy']);
        
        if ($classId) {
            $query->where('e_class_id', $classId);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }
        
        $materials = $query->orderBy('created_at', 'desc')->paginate(20);
        $classes = EClass::orderBy('name')->get();
        
        // Statistics
        $statistics = [
            'total' => Material::count(),
            'this_month' => Material::whereMonth('created_at', now()->month)->count(),
            'by_teacher' => Material::distinct('uploaded_by')->count(),
            'by_class' => Material::distinct('e_class_id')->count(),
        ];
        
        return view('admin.materials.index', compact('materials', 'classes', 'statistics'));
    }

    /**
     * Show the form for creating a new material.
     */
    public function create()
    {
        $classes = EClass::orderBy('name')->get();
        return view('admin.materials.create', compact('classes'));
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,mkv|max:' . config('upload.material_max_kb'),
        ]);

        // Upload file
        $filePath = FileUploadService::uploadMaterial($request->file('file'));
        if (!$filePath) {
            return back()->withErrors('File upload gagal')->withInput();
        }

        // Create material
        Material::create([
            'e_class_id' => $validated['e_class_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'uploaded_by' => auth()->id(),
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'upload_material',
            'description' => "Admin upload materi '{$validated['title']}' untuk kelas " . EClass::find($validated['e_class_id'])->name,
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.materials.index')
                        ->with('success', 'Materi berhasil diunggah!');
    }

    /**
     * Display the specified material.
     */
    public function show(Material $material)
    {
        return view('admin.materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified material.
     */
    public function edit(Material $material)
    {
        $classes = EClass::orderBy('name')->get();
        return view('admin.materials.edit', compact('material', 'classes'));
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'title' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,mkv|max:' . config('upload.material_max_kb'),
        ]);

        $oldClass = $material->eClass->name;
        $newClass = EClass::find($validated['e_class_id'])->name;

        // If new file uploaded, replace old file
        if ($request->hasFile('file')) {
            $filePath = FileUploadService::uploadMaterial($request->file('file'));
            if (!$filePath) {
                return back()->withErrors('File upload gagal')->withInput();
            }
            $validated['file_path'] = $filePath;
        }

        $material->update($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_material',
            'description' => "Admin update materi '{$material->title}' dari kelas {$oldClass} ke {$newClass}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.materials.index')
                        ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy(Request $request, Material $material)
    {
        $title = $material->title;
        $className = $material->eClass->name;

        // Delete physical file first (avoid orphaned files)
        if ($material->file_path) {
            FileUploadService::deleteFile($material->file_path);
        }

        $material->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_material',
            'description' => "Admin hapus materi '{$title}' dari kelas {$className}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.materials.index')
                        ->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Download material file.
     */
    public function download(Material $material)
    {
        $relative = ltrim($material->file_path ?? '', '/');
        abort_if($relative === '', 404);

        // Many uploads store paths like: storage/materials/xxx.ext
        $normalized = preg_replace('#^storage/#', '', $relative);

        $candidates = [
            storage_path('app/public/' . $normalized),
            storage_path('app/' . $relative),
            storage_path('app/' . $normalized),
        ];

        $fullPath = null;
        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                $fullPath = $candidate;
                break;
            }
        }

        abort_unless($fullPath, 404);

        $ext = pathinfo($relative, PATHINFO_EXTENSION);
        $downloadName = trim(($material->title ?: 'material') . ($ext ? '.' . $ext : ''));

        return response()->download($fullPath, $downloadName);
    }

    /**
     * Preview material file (inline).
     */
    public function preview(Material $material)
    {
        $relative = ltrim($material->file_path ?? '', '/');
        abort_if($relative === '', 404);

        $normalized = preg_replace('#^storage/#', '', $relative);

        $candidates = [
            storage_path('app/public/' . $normalized),
            storage_path('app/' . $relative),
            storage_path('app/' . $normalized),
        ];

        $fullPath = null;
        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                $fullPath = $candidate;
                break;
            }
        }

        abort_unless($fullPath, 404);

        // Inline preview for common previewable types
        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $previewable = ['pdf', 'png', 'jpg', 'jpeg', 'gif'];
        abort_if(!in_array($ext, $previewable, true), 415);

        $downloadName = trim(($material->display_name ?: $material->title ?: 'material') . '.' . $ext);
        return response()->file($fullPath, [
            'Content-Disposition' => 'inline; filename="' . addslashes($downloadName) . '"',
        ]);
    }

    /**
     * Get material statistics.
     */
    public function statistics()
    {
        $totalMaterials = Material::count();
        $totalSize = Material::selectRaw('SUM(FILE_SIZE(CONCAT("app/materials/", file_path))) as total_size')->first()->total_size ?? 0;
        $byClass = Material::with('eClass')
            ->selectRaw('e_class_id, COUNT(*) as count')
            ->groupBy('e_class_id')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.materials.statistics', compact('totalMaterials', 'totalSize', 'byClass'));
    }
}
