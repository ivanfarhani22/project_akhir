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
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,mkv|max:100000', // max 100MB
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
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,mkv|max:100000',
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
        if (!file_exists(storage_path('app/materials/' . $material->file_path))) {
            abort(404, 'File not found');
        }

        return response()->download(
            storage_path('app/materials/' . $material->file_path),
            $material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION)
        );
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
