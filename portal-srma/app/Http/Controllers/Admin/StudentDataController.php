<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentData;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentDataController extends Controller
{
    public function index()
    {
        $studentData = StudentData::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->paginate(20);
        
        $academicYears = StudentData::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');
            
        return view('admin.student-data.index', compact('studentData', 'academicYears'));
    }

    public function create()
    {
        return view('admin.student-data.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'class_name' => 'required|string|max:50',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
            'study_groups' => 'required|integer|min:1',
        ]);

        $studentData = StudentData::create($validated);

        ActivityLog::log('create', "Menambah data siswa: {$validated['class_name']} ({$validated['academic_year']})", StudentData::class, $studentData->id);

        return redirect()->route('admin.student-data.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(StudentData $studentData)
    {
        return view('admin.student-data.edit', compact('studentData'));
    }

    public function update(Request $request, StudentData $studentData)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'class_name' => 'required|string|max:50',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
            'study_groups' => 'required|integer|min:1',
        ]);

        $studentData->update($validated);

        ActivityLog::log('update', "Memperbarui data siswa: {$studentData->class_name} ({$studentData->academic_year})", StudentData::class, $studentData->id);

        return redirect()->route('admin.student-data.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(StudentData $studentData)
    {
        $className = $studentData->class_name;
        $academicYear = $studentData->academic_year;
        
        $studentData->delete();

        ActivityLog::log('delete', "Menghapus data siswa: {$className} ({$academicYear})", StudentData::class, null);

        return redirect()->route('admin.student-data.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
