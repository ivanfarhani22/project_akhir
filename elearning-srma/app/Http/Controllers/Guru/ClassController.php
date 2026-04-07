<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource (classes taught by this guru).
     */
    public function index()
    {
        $classes = EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))
            ->with(['subject', 'students', 'materials', 'assignments'])
            ->get();

        return view('guru.classes.index', compact('classes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EClass $class)
    {
        // Verify this guru teaches this class
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $class->load(['subject', 'students', 'materials', 'assignments' => function ($query) {
            $query->orderBy('deadline', 'desc');
        }]);

        return view('guru.classes.show', compact('class'));
    }

    public function create()
    {
        abort(403, 'Admin only');
    }

    public function store(Request $request)
    {
        abort(403, 'Admin only');
    }

    public function edit(string $id)
    {
        abort(403, 'Admin only');
    }

    public function update(Request $request, string $id)
    {
        abort(403, 'Admin only');
    }

    public function destroy(string $id)
    {
        abort(403, 'Admin only');
    }
}
