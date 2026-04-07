<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        // Paginate results (15 per page)
        $subjects = $query->paginate(15);
        
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects|max:10',
            'description' => 'nullable|string',
        ]);

        Subject::create($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_subject',
            'description' => "Admin membuat mata pelajaran: {$request->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil dibuat!');
    }

    public function show(Subject $subject)
    {
        $subject->load('classes');
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code,' . $subject->id . '|max:10',
            'description' => 'nullable|string',
        ]);

        $subject->update($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_subject',
            'description' => "Admin update mata pelajaran: {$subject->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function destroy(Request $request, Subject $subject)
    {
        $subjectName = $subject->name;
        $subject->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_subject',
            'description' => "Admin menghapus mata pelajaran: {$subjectName}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}
