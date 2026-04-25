<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Display a listing of all assignments (admin view).
     */
    public function index()
    {
        $classId = request('class');
        $search = request('search');

        $query = Assignment::with([
            'eClass' => fn($q) => $q->with(['classSubjects.teacher']),
            'classSubject' => fn($q) => $q->with(['eClass', 'subject', 'teacher']),
        ])
        ->withCount([
            'submissions as submissions_count',
            'submissions as pending_count' => fn($q) => $q->whereNull('submitted_at'),
        ]);

        if ($classId) {
            // Support both schemas:
            // - new: assignments.class_subject_id -> class_subjects.e_class_id
            // - legacy: assignments.e_class_id
            $query->where(function ($q) use ($classId) {
                $q->whereHas('classSubject', fn($q2) => $q2->where('e_class_id', $classId))
                  ->orWhere('e_class_id', $classId);
            });
        }

        if ($search) {
            $query->where('title', 'like', "%$search%");
        }

        $assignments = $query->orderBy('deadline', 'desc')->paginate(20);
        $classes = EClass::orderBy('name')->get();

        // Statistics
        $statistics = [
            'total' => Assignment::count(),
            'this_month' => Assignment::whereMonth('created_at', now()->month)->count(),
            'submission_rate' => 0,
            'pending_grading' => Grade::whereNull('score')->count(),
        ];

        return view('admin.assignments.index', compact('assignments', 'classes', 'statistics'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create()
    {
        $classes = EClass::orderBy('name')->get();
        return view('admin.assignments.create', compact('classes'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'class_subject_id' => 'required|exists:class_subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after:now',
            // max_score sengaja dihapus dari input/validasi. Nilai diberikan saat siswa mengumpulkan tugas.
            'file' => 'nullable|file|max:' . config('upload.assignment_max_kb'),
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
            // Store like 'storage/...' to match existing patterns elsewhere in project
            $filePath = 'storage/' . ltrim($filePath, '/');
        }

        $assignment = Assignment::create([
            'e_class_id' => $validated['e_class_id'],
            'class_subject_id' => $validated['class_subject_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'deadline' => $validated['deadline'],
            'file_path' => $filePath,
            'created_by' => auth()->id(),
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_assignment',
            'description' => "Admin buat tugas '{$validated['title']}' untuk kelas " . EClass::find($validated['e_class_id'])->name,
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.assignments.show', $assignment)
                        ->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Display the specified assignment with submissions.
     */
    public function show(Assignment $assignment)
    {
        $assignment->load([
            'eClass' => fn($q) => $q->with(['classSubjects.teacher', 'classSubjects.subject']),
            'classSubject' => fn($q) => $q->with(['eClass', 'subject', 'teacher']),
        ]);

        $submissionsQuery = Submission::where('assignment_id', $assignment->id)
            ->with('student', 'grade')
            ->orderBy('submitted_at', 'desc');

        $submissions = $submissionsQuery->get();

        $statistics = [
            // Prefer classSubject->eClass (current schema). Fallback to eClass if it exists.
            'total_students' => optional(optional($assignment->classSubject)->eClass ?? $assignment->eClass)->students()->count() ?? 0,
            'submitted' => (clone $submissionsQuery)->whereNotNull('submitted_at')->count(),
            'pending' => (clone $submissionsQuery)->whereNull('submitted_at')->count(),
            'graded' => (clone $submissionsQuery)->whereHas('grade')->count(),
            'ungraded' => (clone $submissionsQuery)->whereNotNull('submitted_at')->whereDoesntHave('grade')->count(),
        ];

        return view('admin.assignments.show', compact('assignment', 'submissions', 'statistics'));
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(Assignment $assignment)
    {
        $classes = EClass::orderBy('name')->get();
        return view('admin.assignments.edit', compact('assignment', 'classes'));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            // max_score sengaja dihapus dari input/validasi. Nilai diberikan saat siswa mengumpulkan tugas.
        ]);

        $oldClass = $assignment->eClass->name;
        $newClass = EClass::find($validated['e_class_id'])->name;

        $assignment->update($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_assignment',
            'description' => "Admin update tugas '{$assignment->title}' dari kelas {$oldClass} ke {$newClass}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.assignments.show', $assignment)
                        ->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(Request $request, Assignment $assignment)
    {
        $title = $assignment->title;
        $className = $assignment->eClass->name;

        // Delete physical files first (best-effort)
        $filesDeleted = 0;

        // 1) Assignment attachment
        if (!empty($assignment->file_path)) {
            $relative = preg_replace('#^storage/#', '', ltrim((string) $assignment->file_path, '/'));
            if ($relative && Storage::disk('public')->exists($relative)) {
                if (Storage::disk('public')->delete($relative)) {
                    $filesDeleted++;
                }
            }
        }

        // 2) Submissions files to avoid orphans + broken references
        $submissionPaths = Submission::where('assignment_id', $assignment->id)
            ->whereNotNull('file_path')
            ->pluck('file_path')
            ->all();

        foreach ($submissionPaths as $p) {
            $relative = preg_replace('#^storage/#', '', ltrim((string) $p, '/'));
            if ($relative && Storage::disk('public')->exists($relative)) {
                if (Storage::disk('public')->delete($relative)) {
                    $filesDeleted++;
                }
            }
        }

        $assignment->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_assignment',
            'description' => "Admin hapus tugas '{$title}' dari kelas {$className} (hapus file: {$filesDeleted})",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.assignments.index')
                        ->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Grade submission.
     */
    public function gradeSubmission(Request $request, Assignment $assignment, Submission $submission)
    {
        // Safety: ensure submission belongs to the assignment from the URL
        abort_if((int) $submission->assignment_id !== (int) $assignment->id, 404);

        $maxScore = $assignment->max_score ?? 100;

        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:' . $maxScore],
            'feedback' => 'nullable|string',
        ]);

        $grade = Grade::updateOrCreate(
            ['submission_id' => $submission->id],
            [
                'score' => $validated['score'],
                'feedback' => $validated['feedback'],
                'graded_by' => auth()->id(),
                'graded_at' => now(),
            ]
        );

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'grade_submission',
            'description' => "Admin beri nilai {$validated['score']} untuk submission tugas {$assignment->title}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Get statistics for all assignments.
     */
    public function statistics()
    {
        $totalAssignments = Assignment::count();
        $totalSubmissions = Submission::count();
        $submissionRate = $totalAssignments > 0 
            ? round(($totalSubmissions / ($totalAssignments * 25)) * 100) // Assuming ~25 students per class
            : 0;

        $byClass = Assignment::with('eClass')
            ->selectRaw('e_class_id, COUNT(*) as count')
            ->groupBy('e_class_id')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.assignments.statistics', compact('totalAssignments', 'totalSubmissions', 'submissionRate', 'byClass'));
    }
}
