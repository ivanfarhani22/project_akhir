<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\EClass;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource (assignments for a class).
     */
    public function index()
    {
        $classId = request('class_id');

        if ($classId) {
            // View assignments untuk kelas tertentu
            $class = EClass::findOrFail($classId);

            if (!$class->isTeachedBy(auth()->id())) {
                abort(403, 'Unauthorized');
            }

            $assignments = Assignment::where('e_class_id', $classId)
                ->with(['classSubject' => fn($q) => $q->with(['subject', 'teacher', 'eClass']), 'submissions'])
                ->orderBy('deadline', 'desc')
                ->get();

            return view('guru.assignments.index', compact('class', 'assignments'));
        } else {
            // View semua assignments untuk guru
            $classes = EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))->get();
            $assignments = Assignment::whereHas('eClass', fn($q) => $q->whereHas('classSubjects', fn($q2) => $q2->where('teacher_id', auth()->id())))
                ->with(['eClass', 'classSubject' => fn($q) => $q->with(['subject', 'teacher', 'eClass']), 'submissions', 'submissions.student'])
                ->orderBy('deadline', 'desc')
                ->get();

            return view('guru.assignments.index-all', compact('classes', 'assignments'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classId = request('class_id');

        if ($classId) {
            $class = EClass::with(['classSubjects' => fn($q) => $q->where('teacher_id', auth()->id())])->findOrFail($classId);

            if (!$class->isTeachedBy(auth()->id())) {
                abort(403, 'Unauthorized');
            }

            // For consistency with admin assignment schema
            $classSubjectId = optional($class->classSubjects->first())->id;

            return view('guru.assignments.create', compact('class', 'classSubjectId'));
        } else {
            // Show form untuk pilih kelas dulu
            $classes = EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))->get();
            return view('guru.assignments.create-select-class', compact('classes'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'class_subject_id' => 'required|exists:class_subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
            // max_score sengaja dihapus dari input/validasi. Nilai diberikan saat siswa mengumpulkan tugas.
            'file' => 'nullable|file|max:10240', // 10MB
        ]);

        $class = EClass::findOrFail($validated['e_class_id']);
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        // Upload file if provided
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = FileUploadService::uploadAssignment($request->file('file'));
            if (!$filePath) {
                return back()->withErrors('File upload failed')->withInput();
            }
            // Normalize to storage/... (consistent with admin)
            $filePath = str_starts_with($filePath, 'storage/') ? $filePath : ('storage/' . ltrim($filePath, '/'));
        }

        // Create assignment
        $assignment = Assignment::create([
            'e_class_id' => $validated['e_class_id'],
            'class_subject_id' => $validated['class_subject_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'deadline' => $validated['deadline'],
            'created_by' => auth()->id(),
        ]);

        // Log activity (use internal ActivityLog model, same style as admin)
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_assignment',
            'description' => "Guru buat tugas '{$assignment->title}' untuk kelas {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()
            ->route('guru.assignments.index', ['class_id' => $class->id])
            ->with('success', 'Assignment created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        $class = $assignment->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $assignment->load(['submissions' => function ($query) {
            $query->with('student')->orderBy('submitted_at', 'desc');
        }]);

        return view('guru.assignments.show', compact('assignment', 'class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        $class = $assignment->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        return view('guru.assignments.edit', compact('assignment', 'class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $class = $assignment->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
            // max_score sengaja dihapus dari input/validasi. Nilai diberikan saat siswa mengumpulkan tugas.
            'file' => 'nullable|file|max:10240', // 10MB
        ]);

        // If new file provided
        if ($request->hasFile('file')) {
            $filePath = FileUploadService::uploadAssignment($request->file('file'));
            if (!$filePath) {
                return back()->withErrors('File upload failed')->withInput();
            }

            if ($assignment->file_path) {
                FileUploadService::deleteFile($assignment->file_path);
            }

            $validated['file_path'] = str_starts_with($filePath, 'storage/') ? $filePath : ('storage/' . ltrim($filePath, '/'));
        }

        $assignment->update($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_assignment',
            'description' => "Guru update tugas '{$assignment->title}' untuk kelas {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()
            ->route('guru.assignments.show', $assignment)
            ->with('success', 'Assignment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        $class = $assignment->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        if ($assignment->file_path) {
            FileUploadService::deleteFile($assignment->file_path);
        }

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_assignment',
            'description' => "Guru hapus tugas '{$assignment->title}' dari kelas {$class->name}",
            'ip_address' => request()->ip(),
            'timestamp' => now(),
        ]);

        $assignment->delete();

        return redirect()
            ->route('guru.assignments.index', ['class_id' => $class->id])
            ->with('success', 'Assignment deleted successfully');
    }
}
