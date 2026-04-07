<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Tampilkan daftar semua kelas dengan search dan pagination
     */
    public function index(Request $request)
    {
        $query = EClass::with('classSubjects.teacher', 'classSubjects.subject', 'students');
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        // Paginate results (10 per page)
        $classes = $query->paginate(10);
        
        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Tampilkan form create kelas
     */
    public function create()
    {
        return view('admin.classes.create');
    }

    /**
     * Simpan kelas baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:e_classes,name',
            'description' => 'nullable|string',
            'day_of_week' => 'nullable|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'room' => 'nullable|string|max:100',
        ]);

        $class = EClass::create($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_class',
            'description' => "Admin membuat kelas: {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)
                        ->with('success', 'Kelas berhasil dibuat! Sekarang tambahkan mata pelajaran.');
    }

    /**
     * Tampilkan detail kelas dengan subjects dan students
     */
    public function show(EClass $class)
    {
        $class->load('classSubjects.teacher', 'classSubjects.subject', 'students');
        $teachers = User::where('role', 'guru')->get();
        $subjects = Subject::all();
        
        return view('admin.classes.show', compact('class', 'teachers', 'subjects'));
    }

    /**
     * Tampilkan form edit kelas
     */
    public function edit(EClass $class)
    {
        $students = User::where('role', 'siswa')->get();
        $enrolledStudents = $class->students->pluck('id')->toArray();
        return view('admin.classes.edit', compact('class', 'students', 'enrolledStudents'));
    }

    /**
     * Update kelas
     */
    public function update(Request $request, EClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:e_classes,name,' . $class->id,
            'description' => 'nullable|string',
            'day_of_week' => 'nullable|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'room' => 'nullable|string|max:100',
            'students' => 'nullable|array',
            'students.*' => 'exists:users,id',
        ]);

        // Update kelas (tanpa students field)
        $classData = $validated;
        unset($classData['students']);
        $class->update($classData);

        // Sync siswa
        if (!empty($validated['students'])) {
            $class->students()->sync($validated['students']);
        } else {
            $class->students()->detach();
        }

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_class',
            'description' => "Admin update kelas: {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)
                        ->with('success', 'Kelas berhasil diperbarui!');
    }

    /**
     * Hapus kelas
     */
    public function destroy(Request $request, EClass $class)
    {
        $className = $class->name;
        $class->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_class',
            'description' => "Admin menghapus kelas: {$className}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.index')
                        ->with('success', 'Kelas berhasil dihapus!');
    }
}
