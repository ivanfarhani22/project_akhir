<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;

class QuizIndexController extends Controller
{
    public function __invoke()
    {
        $myClassIds = auth()->user()->classes()->pluck('e_classes.id');

        $quizzes = Quiz::query()
            ->whereHas('assignment', function ($q) use ($myClassIds) {
                $q->whereIn('e_class_id', $myClassIds);
            })
            ->with(['assignment', 'assignment.eClass', 'assignment.classSubject.subject', 'assignment.classSubject.teacher'])
            ->latest('updated_at')
            ->paginate(20);

        return view('siswa.quizzes.index', compact('quizzes'));
    }
}
