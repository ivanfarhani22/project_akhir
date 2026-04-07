<?php

namespace App\Http\Controllers\Admin;

use App\Models\EClass;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassStudentController extends Controller
{
    /**
     * Show students in a class
     */
    public function index(EClass $class)
    {
        $students = $class->students()->paginate(20);
        $allStudents = User::where('role', 'siswa')->get();
        $enrolledStudentIds = $class->students()->pluck('users.id')->toArray();
        
        return view('admin.classes.students', compact('class', 'students', 'allStudents', 'enrolledStudentIds'));
    }

    /**
     * Add student to class
     */
    public function store(Request $request, EClass $class)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);

        $studentIds = $request->input('student_ids');
        
        // Sync without detaching (hanya menambah, tidak menghapus)
        $class->students()->syncWithoutDetaching($studentIds);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'add_students_to_class',
            'description' => "Admin menambahkan " . count($studentIds) . " siswa ke kelas {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)->with('success', count($studentIds) . ' siswa berhasil ditambahkan ke kelas!');
    }

    /**
     * Remove student from class
     */
    public function destroy(EClass $class, User $student)
    {
        if (!$class->students->contains($student->id)) {
            return redirect()->back()->with('warning', 'Siswa tidak terdaftar di kelas ini!');
        }

        $class->students()->detach($student->id);

        return redirect()->back()->with('success', "{$student->name} berhasil dihapus dari kelas!");
    }

}
