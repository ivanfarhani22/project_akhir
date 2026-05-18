<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;

class QuizIndexController extends Controller
{
    public function __invoke()
    {
        $quizzes = Quiz::query()
            ->with(['assignment', 'assignment.eClass', 'assignment.classSubject.subject'])
            ->latest('updated_at')
            ->paginate(20);

        return view('admin.quizzes.index', compact('quizzes'));
    }
}
