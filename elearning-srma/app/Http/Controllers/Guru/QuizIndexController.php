<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassSubject;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class QuizIndexController extends Controller
{
    public function __invoke()
    {
        // List quizzes for assignments taught by the teacher
        $quizzes = Quiz::query()
            ->whereHas('assignment.classSubject', function ($q) {
                $q->where('teacher_id', auth()->id());
            })
            ->with(['assignment', 'assignment.eClass', 'assignment.classSubject.subject'])
            ->latest('updated_at')
            ->paginate(20);

        // Provide a list of class subjects for creation
        $classSubjects = ClassSubject::query()
            ->where('teacher_id', auth()->id())
            ->with(['eClass', 'subject'])
            ->orderByDesc('id')
            ->get();

        return view('guru.quizzes.index', compact('quizzes', 'classSubjects'));
    }

    /**
     * Quiz-first flow: Create an Assignment intended for a Quiz and redirect to manage screen.
     */
    public function storeQuizAssignment(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
        ]);

        $classSubject = ClassSubject::with(['eClass', 'subject'])->findOrFail($validated['class_subject_id']);
        abort_if($classSubject->teacher_id !== auth()->id(), 403, 'Unauthorized');

        $data = [
            'e_class_id' => $classSubject->e_class_id,
            'class_subject_id' => $classSubject->id,
            'title' => $validated['title'],
            'type' => 'quiz',
            'description' => $validated['description'] ?? null,
            'file_path' => null,
            'deadline' => $validated['deadline'],
        ];

        // Avoid errors if the column doesn't exist in this DB
        if (Schema::hasColumn('assignments', 'created_by')) {
            $data['created_by'] = auth()->id();
        }

        $assignment = Assignment::create($data);

        return redirect()
            ->route('guru.quizzes.manage', $assignment)
            ->with('success', 'Quiz berhasil dibuat. Silakan kelola quiz-nya.');
    }
}
