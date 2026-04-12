<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\Material;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource (materials for a class).
     */
    public function index()
    {
        $classId = request('class_id');

        if (!$classId) {
            // Tidak ada filter "semua kelas" untuk saat ini.
            // Arahkan guru untuk memilih kelas terlebih dahulu.
            return redirect()->route('guru.materials.create');
        }

        // View materials untuk kelas tertentu
        $class = EClass::findOrFail($classId);

        // Check apakah guru mengajar kelas ini (via classSubjects)
        $isTeacher = $class->classSubjects()->where('teacher_id', auth()->id())->exists();
        if (!$isTeacher) {
            abort(403, 'Unauthorized');
        }

        $materials = Material::where('e_class_id', $classId)
            ->with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.materials.index', compact('class', 'materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classId = request('class_id');
        
        if ($classId) {
            $class = EClass::findOrFail($classId);
            
            if (!$class->isTeachedBy(auth()->id())) {
                abort(403, 'Unauthorized');
            }
            
            return view('guru.materials.create', compact('class'));
        } else {
            // Show form untuk pilih kelas dulu
            $classes = EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))->get();
            return view('guru.materials.create-select-class', compact('classes'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file',
        ]);

        $class = EClass::findOrFail($validated['e_class_id']);
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        // Upload file
        $filePath = FileUploadService::uploadMaterial($request->file('file'));
        if (!$filePath) {
            return back()->withErrors('File upload failed')->withInput();
        }

        // Create material
        $material = Material::create([
            'e_class_id' => $validated['e_class_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'version' => 1,
            'uploaded_by' => auth()->id(),
        ]);

        // Log activity
        activity()
            ->performedOn($material)
            ->causedBy(auth()->user())
            ->log('Material uploaded: ' . $material->title);

        return redirect()
            ->route('guru.materials.index', ['class_id' => $class->id])
            ->with('success', 'Material uploaded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        $class = $material->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        return view('guru.materials.show', compact('material', 'class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $class = $material->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        return view('guru.materials.edit', compact('material', 'class'));
    }

    /**
     * Update the specified resource in storage (creates new version).
     */
    public function update(Request $request, Material $material)
    {
        $class = $material->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file',
        ]);

        // If new file provided, create new version
        if ($request->hasFile('file')) {
            $filePath = FileUploadService::uploadMaterial($request->file('file'));
            if (!$filePath) {
                return back()->withErrors('File upload failed')->withInput();
            }

            // Delete old file
            FileUploadService::deleteFile($material->file_path);

            $material->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'file_path' => $filePath,
                'file_type' => $request->file('file')->getClientOriginalExtension(),
                'version' => $material->version + 1,
            ]);

            activity()
                ->performedOn($material)
                ->causedBy(auth()->user())
                ->log('Material versioned: ' . $material->title . ' (v' . $material->version . ')');
        } else {
            $material->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            activity()
                ->performedOn($material)
                ->causedBy(auth()->user())
                ->log('Material updated: ' . $material->title);
        }

        return redirect()
            ->route('guru.materials.show', $material)
            ->with('success', 'Material updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $class = $material->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        FileUploadService::deleteFile($material->file_path);
        
        activity()
            ->performedOn($material)
            ->causedBy(auth()->user())
            ->log('Material deleted: ' . $material->title);

        $material->delete();

        return redirect()
            ->route('guru.materials.index', ['class_id' => $class->id])
            ->with('success', 'Material deleted successfully');
    }
}
