<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class GradeController extends Controller
{
    /**
     * Display a listing of submissions for grading.
     */
    public function index()
    {
        $assignmentId = request('assignment_id');
        
        if ($assignmentId) {
            // View submissions untuk assignment tertentu
            $assignment = Assignment::findOrFail($assignmentId);
            
            // Verify teacher owns this assignment
            if (!$assignment->eClass->isTeachedBy(auth()->id())) {
                abort(403, 'Unauthorized');
            }

            $submissions = Submission::where('assignment_id', $assignmentId)
                ->with(['student', 'grade'])
                ->orderBy('submitted_at', 'desc')
                ->get();

            return view('guru.grades.index', compact('assignment', 'submissions'));
        } else {
            // View semua submissions untuk guru
            $assignments = Assignment::whereHas('eClass', fn($q) => $q->whereHas('classSubjects', fn($q2) => $q2->where('teacher_id', auth()->id())))->get();
            $submissions = Submission::whereHas('assignment.eClass', fn($q) => $q->whereHas('classSubjects', fn($q2) => $q2->where('teacher_id', auth()->id())))
                ->with(['student', 'assignment', 'grade'])
                ->orderBy('submitted_at', 'desc')
                ->get();

            return view('guru.grades.index-all', compact('assignments', 'submissions'));
        }
    }

    /**
     * Show the form for grading a submission.
     */
    public function edit(Submission $submission)
    {
        $assignment = $submission->assignment;
        if (!$assignment->eClass->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $submission->load('grade', 'student');

        return view('guru.grades.edit', compact('submission', 'assignment'));
    }

    /**
     * Store/Update grade for a submission.
     */
    public function update(Request $request, Submission $submission)
    {
        $assignment = $submission->assignment;
        if (!$assignment->eClass->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        // Find or create grade
        $grade = Grade::where('submission_id', $submission->id)->first();
        
        if ($grade) {
            $grade->update([
                'score' => $validated['score'],
                'feedback' => $validated['feedback'],
                'graded_at' => now(),
            ]);
        } else {
            $grade = Grade::create([
                'submission_id' => $submission->id,
                'student_id' => $submission->student_id,
                'assignment_id' => $assignment->id,
                'score' => $validated['score'],
                'feedback' => $validated['feedback'],
                'graded_at' => now(),
            ]);
        }

        // Log aktivitas (tanpa ketergantungan helper activity())
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'grade_saved',
            'description' => 'Grade given to ' . $submission->student->name . ': ' . $validated['score'],
            'model_type' => Grade::class,
            'model_id' => $grade->id,
        ]);

        return redirect()
            ->route('guru.grades.index', ['assignment_id' => $assignment->id])
            ->with('success', 'Grade saved successfully');
    }

    /**
     * Not used - grading only via edit/update.
     */
    public function create()
    {
        abort(403, 'Not available');
    }

    public function store(Request $request)
    {
        abort(403, 'Not available');
    }

    public function show(string $id)
    {
        abort(403, 'Not available');
    }

    public function destroy(string $id)
    {
        abort(403, 'Not available');
    }
}
