<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\ClassSubject;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource (classes taught by this guru).
     */
    public function index()
    {
        // Get classSubjects directly to create one card per subject per class
        $classSubjects = ClassSubject::where('teacher_id', auth()->id())
            ->withCount(['materials', 'assignments'])
            ->with([
                'eClass' => fn($q) => $q->with('students'),
                'subject',
            ])
            ->orderBy('e_class_id')
            ->get();

        return view('guru.classes.index', compact('classSubjects'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassSubject $classSubject)
    {
        // Ensure the class-subject belongs to the authenticated guru
        abort_unless((int) $classSubject->teacher_id === (int) auth()->id(), 403, 'Unauthorized');

        $classSubject->load([
            'eClass' => fn ($q) => $q->with('students'),
            'subject',
        ]);

        $materials = Material::where('class_subject_id', $classSubject->id)
            ->with(['classSubject.subject'])
            ->latest()
            ->get();

        $assignments = Assignment::where('class_subject_id', $classSubject->id)
            ->with(['classSubject.subject'])
            ->latest()
            ->get();

        $schedules = Schedule::where('class_subject_id', $classSubject->id)
            ->with(['classSubject.subject'])
            ->orderByRaw("FIELD(day_of_week, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('start_time')
            ->get();

        return view('guru.classes.show', compact('classSubject', 'materials', 'assignments', 'schedules'));
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
