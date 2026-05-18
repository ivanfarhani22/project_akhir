<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    public function index(Assignment $assignment)
    {
        $quiz = Quiz::firstOrCreate(['assignment_id' => $assignment->id]);
        $quiz->load(['questions' => fn ($q) => $q->orderBy('order')]);

        return view('admin.quizzes.manage', compact('assignment', 'quiz'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        $quiz = Quiz::firstOrCreate(['assignment_id' => $assignment->id]);

        $validated = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'points' => 'required|integer|min:0',
            'options_text' => 'nullable|string',
            'correct_answer' => 'nullable|string',
        ]);

        $options = null;
        if ($validated['type'] === 'multiple_choice') {
            $raw = (string) ($validated['options_text'] ?? '');
            $lines = collect(preg_split('/\r\n|\r|\n/', $raw))
                ->map(fn ($s) => trim($s))
                ->filter(fn ($s) => $s !== '')
                ->values();

            $options = $lines->all();
        }

        $maxOrder = (int) QuizQuestion::where('quiz_id', $quiz->id)->max('order');

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => $validated['question'],
            'type' => $validated['type'],
            'points' => (int) $validated['points'],
            'options' => $options,
            'correct_answer' => $validated['correct_answer'] ?? null,
            'order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Soal quiz berhasil ditambahkan.');
    }

    public function update(Request $request, Assignment $assignment, QuizQuestion $question)
    {
        $quiz = Quiz::where('assignment_id', $assignment->id)->firstOrFail();
        abort_if($question->quiz_id !== $quiz->id, 404);

        $validated = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'points' => 'required|integer|min:0',
            'options_text' => 'nullable|string',
            'correct_answer' => 'nullable|string',
        ]);

        $options = null;
        if ($validated['type'] === 'multiple_choice') {
            $raw = (string) ($validated['options_text'] ?? '');
            $lines = collect(preg_split('/\r\n|\r|\n/', $raw))
                ->map(fn ($s) => trim($s))
                ->filter(fn ($s) => $s !== '')
                ->values();

            $options = $lines->all();
        }

        $question->update([
            'question' => $validated['question'],
            'type' => $validated['type'],
            'points' => (int) $validated['points'],
            'options' => $options,
            'correct_answer' => $validated['correct_answer'] ?? null,
        ]);

        return back()->with('success', 'Soal quiz berhasil diperbarui.');
    }

    public function destroy(Assignment $assignment, QuizQuestion $question)
    {
        $quiz = Quiz::where('assignment_id', $assignment->id)->firstOrFail();
        abort_if($question->quiz_id !== $quiz->id, 404);

        $question->delete();

        return back()->with('success', 'Soal quiz berhasil dihapus.');
    }
}
