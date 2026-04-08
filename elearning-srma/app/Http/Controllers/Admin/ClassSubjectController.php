<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\ClassSubject;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    /**
     * Tampilkan form add subject ke kelas
     */
    public function create(EClass $class)
    {
        // Ambil subjects yang belum ditambahkan ke kelas ini
        $addedSubjects = $class->classSubjects->pluck('subject_id')->toArray();
        $availableSubjects = Subject::whereNotIn('id', $addedSubjects)->get();
        $teachers = User::where('role', 'guru')->get();
        
        return view('admin.class-subjects.create', compact('class', 'availableSubjects', 'teachers'));
    }

    /**
     * Simpan subject ke kelas
     */
    public function store(Request $request, EClass $class)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        // Cek apakah subject sudah ada di kelas
        $exists = ClassSubject::where('e_class_id', $class->id)
                            ->where('subject_id', $validated['subject_id'])
                            ->exists();
        
        if ($exists) {
            return redirect()->back()->with('error', 'Mata pelajaran ini sudah ada di kelas ini!');
        }

        // Verify teacher exists and has guru role
        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->role !== 'guru') {
            return redirect()->back()->with('error', 'User yang dipilih bukan guru!');
        }

        ClassSubject::create([
            'e_class_id' => $class->id,
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'description' => $validated['description'],
        ]);

        $subject = Subject::find($validated['subject_id']);
        $teacher = User::find($validated['teacher_id']);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'add_class_subject',
            'description' => "Admin menambah {$subject->name} (Guru: {$teacher->name}) ke kelas {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)
                        ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit subject di kelas
     */
    public function edit(EClass $class, ClassSubject $classSubject)
    {
        if ($classSubject->e_class_id != $class->id) {
            abort(404);
        }

        $teachers = User::where('role', 'guru')->get();
        
        return view('admin.class-subjects.edit', compact('class', 'classSubject', 'teachers'));
    }

    /**
     * Update subject di kelas
     */
    public function update(Request $request, EClass $class, ClassSubject $classSubject)
    {
        if ($classSubject->e_class_id != $class->id) {
            abort(404);
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $oldTeacher = $classSubject->teacher->name;
        $classSubject->update($validated);
        $newTeacher = $classSubject->teacher->name;

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_class_subject',
            'description' => "Admin update guru {$classSubject->subject->name} di kelas {$class->name} dari {$oldTeacher} ke {$newTeacher}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)
                        ->with('success', 'Guru mata pelajaran berhasil diperbarui!');
    }

    /**
     * Hapus subject dari kelas
     */
    public function destroy(Request $request, EClass $class, ClassSubject $classSubject)
    {
        if ($classSubject->e_class_id != $class->id) {
            abort(404);
        }

        $subjectName = $classSubject->subject->name;
        $classSubject->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'remove_class_subject',
            'description' => "Admin menghapus {$subjectName} dari kelas {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)
                        ->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}
