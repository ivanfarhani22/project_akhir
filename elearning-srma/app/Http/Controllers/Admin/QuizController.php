<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function upsert(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'shuffle_questions' => 'nullable|boolean',
            'shuffle_options' => 'nullable|boolean',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);

        $quiz = Quiz::updateOrCreate(
            ['assignment_id' => $assignment->id],
            [
                'shuffle_questions' => (bool) ($validated['shuffle_questions'] ?? false),
                'shuffle_options' => (bool) ($validated['shuffle_options'] ?? false),
                'time_limit_minutes' => $validated['time_limit_minutes'] ?? null,
                'attempts_allowed' => $validated['attempts_allowed'],
                'status' => $validated['status'],
            ]
        );

        return back()->with('success', 'Quiz settings saved.');
    }
}
