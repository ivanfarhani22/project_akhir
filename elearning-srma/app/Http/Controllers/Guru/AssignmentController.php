<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\EClass;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource (assignments for a class).
     */
    public function index()
    {
        $classId = request('class_id');
        
        if ($classId) {
            // View assignments untuk kelas tertentu
            $class = EClass::findOrFail($classId);
            
            if (!$class->isTeachedBy(auth()->id())) {
                abort(403, 'Unauthorized');
            }

            $assignments = Assignment::where('e_class_id', $classId)
                ->orderBy('deadline', 'desc')
                ->get();

            return view('guru.assignments.index', compact('class', 'assignments'));
        } else {
            // View semua assignments untuk guru
            $classes = EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))->get();
            $assignments = Assignment::whereHas('eClass', fn($q) => $q->whereHas('classSubjects', fn($q2) => $q2->where('teacher_id', auth()->id())))
                ->with('eClass')
                ->orderBy('deadline', 'desc')
                ->get();

            return view('guru.assignments.index-all', compact('classes', 'assignments'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classId = request('class_id');
        
        if ($classId) {
            $class = EClass::findOrFail($classId);
            
            if (!$class->isTeachedBy(auth()->id())) {
                abort(403, 'Unauthorized');
            }
            
            return view('guru.assignments.create', compact('class'));
        } else {
            // Show form untuk pilih kelas dulu
            $classes = EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))->get();
            return view('guru.assignments.create-select-class', compact('classes'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
            'file' => 'nullable|file',
        ]);

        $class = EClass::findOrFail($validated['e_class_id']);
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        // Upload file if provided
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = FileUploadService::uploadAssignment($request->file('file'));
            if (!$filePath) {
                return back()->withErrors('File upload failed')->withInput();
            }
        }

        // Create assignment
        $assignment = Assignment::create([
            'e_class_id' => $validated['e_class_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'deadline' => $validated['deadline'],
        ]);

        // Log activity
        activity()
            ->performedOn($assignment)
            ->causedBy(auth()->user())
            ->log('Assignment created: ' . $assignment->title);

        return redirect()
            ->route('guru.assignments.index', ['class_id' => $class->id])
            ->with('success', 'Assignment created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        $class = $assignment->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $assignment->load(['submissions' => function ($query) {
            $query->with('student')->orderBy('submitted_at', 'desc');
        }]);

        return view('guru.assignments.show', compact('assignment', 'class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        $class = $assignment->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        return view('guru.assignments.edit', compact('assignment', 'class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $class = $assignment->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
            'file' => 'nullable|file',
        ]);

        // If new file provided
        if ($request->hasFile('file')) {
            $filePath = FileUploadService::uploadAssignment($request->file('file'));
            if (!$filePath) {
                return back()->withErrors('File upload failed')->withInput();
            }

            if ($assignment->file_path) {
                FileUploadService::deleteFile($assignment->file_path);
            }

            $validated['file_path'] = $filePath;
        }

        $assignment->update($validated);

        activity()
            ->performedOn($assignment)
            ->causedBy(auth()->user())
            ->log('Assignment updated: ' . $assignment->title);

        return redirect()
            ->route('guru.assignments.show', $assignment)
            ->with('success', 'Assignment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        $class = $assignment->eClass;
        if (!$class->isTeachedBy(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        if ($assignment->file_path) {
            FileUploadService::deleteFile($assignment->file_path);
        }

        activity()
            ->performedOn($assignment)
            ->causedBy(auth()->user())
            ->log('Assignment deleted: ' . $assignment->title);

        $assignment->delete();

        return redirect()
            ->route('guru.assignments.index', ['class_id' => $class->id])
            ->with('success', 'Assignment deleted successfully');
    }
}
