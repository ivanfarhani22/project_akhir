<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassSubject;
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
        $classSubjectId = request('class_subject_id');
        if ($classSubjectId) {
            $classSubject = ClassSubject::findOrFail($classSubjectId);
            return $this->indexByClassSubject($classSubject);
        }

        // Backward-compat: masih dukung class_id untuk beberapa halaman lama
        $classId = request('class_id');

        // Hide internal quiz-generated assignments from the Assignments UI.
        // Preferred marker: type = 'quiz'
        // Legacy fallback (only for old rows without `type` backfilled yet): description = 'Quiz'
        $hideQuizAssignments = function ($q) {
            $q->where(function ($qq) {
                $qq->where('type', '!=', 'quiz')
                    ->orWhereNull('type');
            })->where(function ($qq) {
                $qq->whereNotNull('type')
                    ->orWhereNull('description')
                    ->orWhere('description', '!=', 'Quiz');
            });
        };

        if ($classId) {
            // View assignments untuk kelas tertentu
            $class = EClass::findOrFail($classId);

            if (!$class->isTeachedBy(auth()->id())) {
                abort(403, 'Unauthorized');
            }

            $assignments = Assignment::where('e_class_id', $classId)
                ->where($hideQuizAssignments)
                ->with(['classSubject' => fn($q) => $q->with(['subject', 'teacher', 'eClass']), 'submissions'])
                ->orderBy('deadline', 'desc')
                ->get();

            return view('guru.assignments.index', compact('class', 'assignments'));
        } else {
            // View semua assignments untuk guru (dipertahankan untuk admin/legacy page)
            $classes = EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))->get();
            $assignments = Assignment::whereHas('eClass', fn($q) => $q->whereHas('classSubjects', fn($q2) => $q2->where('teacher_id', auth()->id())))
                ->where($hideQuizAssignments)
                ->with(['eClass', 'classSubject' => fn($q) => $q->with(['subject', 'teacher', 'eClass']), 'submissions', 'submissions.student'])
                ->orderBy('deadline', 'desc')
                ->get();

            return view('guru.assignments.index-all', compact('classes', 'assignments'));
        }
    }

    /**
     * New (preferred): list assignments for a specific class_subject.
     */
    public function indexByClassSubject(ClassSubject $classSubject)
    {
        abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');
        $classSubject->load(['eClass', 'subject']);
        $class = $classSubject->eClass;

        $assignments = Assignment::query()
            ->where('class_subject_id', $classSubject->id)
            ->with(['classSubject' => fn($q) => $q->with(['subject', 'teacher', 'eClass']), 'submissions', 'submissions.student'])
            ->orderBy('deadline', 'desc')
            ->get();

        return view('guru.assignments.index', compact('class', 'assignments', 'classSubject'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classSubjectId = request('class_subject_id');

        // Required: create harus scoped ke class_subject
        if ($classSubjectId) {
            $classSubject = ClassSubject::findOrFail($classSubjectId);
            abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');

            $classSubject->load(['eClass', 'subject']);
            $class = $classSubject->eClass;

            return view('guru.assignments.create', compact('class', 'classSubject'));
        }

        // Default: show selector list by class_subject (mapel) like Materials
        $classSubjects = ClassSubject::where('teacher_id', auth()->id())
            ->with(['eClass' => fn ($q) => $q->withCount('students'), 'subject'])
            ->orderBy('e_class_id')
            ->get();

        return view('guru.assignments.create-select-class', compact('classSubjects'));
    }

    /**
     * New (preferred): show create form scoped to class_subject.
     */
    public function createByClassSubject(ClassSubject $classSubject)
    {
        abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');
        $classSubject->load(['eClass', 'subject']);
        $class = $classSubject->eClass;

        return view('guru.assignments.create', compact('class', 'classSubject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Note: tetap ada untuk backward-compat, tapi wajib ada class_subject_id
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after:now',
            'file' => 'nullable|file|max:' . config('upload.assignment_max_kb'),
        ]);

        $classSubject = ClassSubject::findOrFail($validated['class_subject_id']);
        abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');

        // Delegate to preferred flow
        return $this->storeByClassSubject($request, $classSubject);
    }

    /**
     * New (preferred): store assignment scoped to class_subject.
     */
    public function storeByClassSubject(Request $request, ClassSubject $classSubject)
    {
        abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after:now',
            'file' => 'nullable|file|max:' . config('upload.assignment_max_kb'),
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = FileUploadService::uploadAssignment($request->file('file'));
            if (!$filePath) {
                return back()->withErrors('File upload failed')->withInput();
            }
            $filePath = str_starts_with($filePath, 'storage/') ? $filePath : ('storage/' . ltrim($filePath, '/'));
        }

        $assignment = Assignment::create([
            'e_class_id' => $classSubject->e_class_id, // keep for compatibility/reporting
            'class_subject_id' => $classSubject->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'deadline' => $validated['deadline'],
            'created_by' => auth()->id(),
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_assignment',
            'description' => "Guru buat tugas '{$assignment->title}' untuk kelas {$classSubject->eClass->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()
            ->route('guru.class-subjects.assignments.index', $classSubject)
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
            'deadline' => 'required|date',
            // max_score sengaja dihapus dari input/validasi. Nilai diberikan saat siswa mengumpulkan tugas.
            'file' => 'nullable|file|max:' . config('upload.assignment_max_kb'),
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
