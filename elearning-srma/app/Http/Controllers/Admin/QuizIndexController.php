<?php

namespace App\Http\Controllers\Admin;

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
        $quizzes = Quiz::query()
            ->with(['questions', 'assignment', 'assignment.eClass', 'assignment.classSubject.subject'])
            ->latest('updated_at')
            ->paginate(20);

        // Untuk menyamakan UI dengan halaman guru (form create membutuhkan list mapel)
        $classSubjects = ClassSubject::query()
            ->with(['eClass', 'subject'])
            ->orderByDesc('id')
            ->get();

        return view('admin.quizzes.index', compact('quizzes', 'classSubjects'));
    }

    /**
     * Admin flow: Create an Assignment intended for a Quiz and redirect to manage screen.
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
            ->route('admin.quizzes.manage', $assignment)
            ->with('success', 'Quiz berhasil dibuat. Silakan kelola quiz-nya.');
    }
}
