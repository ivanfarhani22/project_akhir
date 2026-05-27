<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassSubject;
use App\Models\Grade;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class GradeController extends Controller
{
    public function index()
    {
        $assignmentId   = request('assignment_id');
        $classSubjectId = request('class_subject_id');

        if ($assignmentId) {
            // ── View per tugas ──────────────────────────────────────────
            $assignment = Assignment::findOrFail($assignmentId);

            if (! $assignment->eClass->isTeachedBy(auth()->id())) {
                abort(403, 'Unauthorized');
            }

            $submissions = Submission::where('assignment_id', $assignmentId)
                ->with(['student', 'grade'])
                ->orderBy('submitted_at', 'desc')
                ->get();

            return view('guru.grades.index', compact('assignment', 'submissions'));
        }

        // ── View semua / filter by class_subject ───────────────────────
        $classSubjects = ClassSubject::where('teacher_id', auth()->id())
            ->with(['eClass', 'subject'])
            ->orderByDesc('id')
            ->get();

        // Dropdown tugas: hanya muncul jika class_subject dipilih
        $assignments = $classSubjectId
            ? Assignment::where('class_subject_id', $classSubjectId)
                ->orderBy('title')
                ->get()
            : collect();

        $query = Submission::with(['student', 'assignment.eClass', 'assignment.classSubject.subject', 'grade'])
            ->whereHas('assignment.classSubject', fn ($q) => $q->where('teacher_id', auth()->id()));

        if ($classSubjectId) {
            $query->whereHas('assignment', fn ($q) => $q->where('class_subject_id', $classSubjectId));
        }

        if ($assignmentId) {
            $query->where('assignment_id', $assignmentId);
        }

        $submissions = $query->orderBy('submitted_at', 'desc')->get();

        return view('guru.grades.index-all', compact('assignments', 'submissions', 'classSubjects', 'classSubjectId'));
    }

    public function edit(Submission $submission)
    {
        $assignment = $submission->assignment;
        abort_unless($assignment, 404);

        $class = $assignment->eClass;
        abort_unless($class, 404);

        if (! $class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $submission->load('grade', 'student');

        return view('guru.grades.edit', compact('submission', 'assignment'));
    }

    public function update(Request $request, Submission $submission)
    {
        $assignment = $submission->assignment;
        abort_unless($assignment, 404);

        $class = $assignment->eClass;
        abort_unless($class, 404);

        if (! $class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'score'    => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $grade = Grade::where('submission_id', $submission->id)->first();

        if ($grade) {
            $grade->update([
                'score'     => $validated['score'],
                'feedback'  => $validated['feedback'],
                'graded_at' => now(),
            ]);
        } else {
            $grade = Grade::create([
                'submission_id' => $submission->id,
                'student_id'    => $submission->student_id,
                'assignment_id' => $assignment->id,
                'score'         => $validated['score'],
                'feedback'      => $validated['feedback'],
                'graded_at'     => now(),
            ]);
        }

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'grade_saved',
            'description' => 'Grade given to ' . $submission->student->name . ': ' . $validated['score'],
            'model_type'  => Grade::class,
            'model_id'    => $grade->id,
        ]);

        // Kembali ke halaman sebelumnya dengan konteks filter terjaga
        return redirect()
            ->route('guru.grades.index', ['assignment_id' => $assignment->id])
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function create()  { abort(403); }
    public function store()   { abort(403); }
    public function show()    { abort(403); }
    public function destroy() { abort(403); }
}