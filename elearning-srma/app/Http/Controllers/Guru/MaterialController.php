<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\Material;
use App\Models\ActivityLog;
use App\Models\ClassSubject;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classId = request('class_id');
        $classSubjectId = request('class_subject_id');

        // New default: if no class filter is provided, show selector (1 card = 1 class_subject)
        if (!$classId && !$classSubjectId) {
            $classSubjects = ClassSubject::where('teacher_id', auth()->id())
                ->with([
                    'eClass' => fn($q) => $q->with('students'),
                    'subject',
                ])
                ->orderBy('e_class_id')
                ->get();

            return view('guru.materials.create-select-class', compact('classSubjects'));
        }

        // Backward-compat: class_subject_id is preferred; if present, use the new scoped index.
        if ($classSubjectId) {
            $classSubject = ClassSubject::whereKey($classSubjectId)
                ->where('teacher_id', auth()->id())
                ->with(['eClass', 'subject'])
                ->firstOrFail();

            return $this->indexByClassSubject($classSubject);
        }

        // Legacy: when only class_id is provided, show materials for class (may still mix if old data exists)
        abort_unless($classId, 404);

        $class = EClass::findOrFail($classId);

        $isTeacher = $class->classSubjects()->where('teacher_id', auth()->id())->exists();
        abort_unless($isTeacher, 403, 'Unauthorized');

        $materials = Material::query()
            ->where('e_class_id', $classId)
            ->with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->get();

        $classSubject = null;
        return view('guru.materials.index', compact('class', 'materials', 'classSubject'));
    }

    /**
     * New (preferred): list materials for a specific class_subject.
     */
    public function indexByClassSubject(ClassSubject $classSubject)
    {
        abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');

        $classSubject->load(['eClass', 'subject']);
        $class = $classSubject->eClass;

        $materials = Material::query()
            ->where('class_subject_id', $classSubject->id)
            ->with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.materials.index', compact('class', 'materials', 'classSubject'));
    }

    /**
     * New (preferred): show create form scoped to class_subject.
     */
    public function createByClassSubject(ClassSubject $classSubject)
    {
        abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');

        $classSubject->load(['eClass', 'subject']);
        $class = $classSubject->eClass;

        return view('guru.materials.create', compact('class', 'classSubject'));
    }

    /**
     * New (preferred): store material scoped to class_subject.
     */
    public function storeByClassSubject(Request $request, ClassSubject $classSubject)
    {
        abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:' . config('upload.material_max_kb'),
        ]);

        // Upload file
        $filePath = FileUploadService::uploadMaterial($request->file('file'));
        if (!$filePath) {
            return back()->withErrors('File upload failed')->withInput();
        }

        $material = Material::create([
            'e_class_id' => $classSubject->e_class_id, // keep for compatibility/reporting
            'class_subject_id' => $classSubject->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'version' => 1,
            'uploaded_by' => auth()->id(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'material_uploaded',
            'description' => 'Material uploaded: ' . $material->title,
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()
            ->route('guru.class-subjects.materials.index', $classSubject)
            ->with('success', 'Material uploaded successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Keep this endpoint as a convenience entry point.
        // The actual flow starts from /guru/materials (selector), then goes to scoped create routes.
        return redirect()->route('guru.materials.index', request()->only(['class_id', 'class_subject_id']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Legacy endpoint: to avoid data mixing, require class_subject_id.
        // Use /guru/class-subjects/{classSubject}/materials/create for the preferred flow.
        $validated = $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'class_subject_id' => 'required|exists:class_subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:' . config('upload.material_max_kb'),
        ]);

        $class = EClass::findOrFail($validated['e_class_id']);

        // Ensure the class_subject belongs to this class & this teacher
        $classSubject = ClassSubject::whereKey($validated['class_subject_id'])
            ->where('e_class_id', $class->id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        // Upload file
        $filePath = FileUploadService::uploadMaterial($request->file('file'));
        if (!$filePath) {
            return back()->withErrors('File upload failed')->withInput();
        }

        // Create material
        $material = Material::create([
            'e_class_id' => $class->id,
            'class_subject_id' => $classSubject->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'version' => 1,
            'uploaded_by' => auth()->id(),
        ]);

        // Log activity (pakai tabel activity_logs bawaan project; tidak bergantung helper activity())
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'material_uploaded',
            'description' => 'Material uploaded: ' . $material->title,
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()
            ->route('guru.class-subjects.materials.index', $classSubject)
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
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:' . config('upload.material_max_kb'),
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

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'material_versioned',
                'description' => 'Material versioned: ' . $material->title . ' (v' . $material->version . ')',
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);
        } else {
            $material->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'material_updated',
                'description' => 'Material updated: ' . $material->title,
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);
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

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'material_deleted',
            'description' => 'Material deleted: ' . $material->title,
            'ip_address' => request()->ip(),
            'timestamp' => now(),
        ]);

        $material->delete();

        return redirect()
            ->route('guru.materials.index', ['class_id' => $class->id])
            ->with('success', 'Material deleted successfully');
    }

    /**
     * Preview material file (inline).
     */
    public function preview(Material $material)
    {
        $class = $material->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

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

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $previewable = ['pdf', 'png', 'jpg', 'jpeg', 'gif'];
        abort_if(!in_array($ext, $previewable, true), 415);

        $downloadName = trim(($material->display_name ?: $material->title ?: 'material') . '.' . $ext);
        return response()->file($fullPath, [
            'Content-Disposition' => 'inline; filename="' . addslashes($downloadName) . '"',
        ]);
    }
}
