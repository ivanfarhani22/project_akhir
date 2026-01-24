<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentDistribution;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentDistributionController extends Controller
{
    public function index()
    {
        $distributions = StudentDistribution::orderBy('academic_year', 'desc')
            ->orderBy('student_count', 'desc')
            ->paginate(30);
        
        $academicYears = StudentDistribution::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');
            
        return view('admin.student-distribution.index', compact('distributions', 'academicYears'));
    }

    public function create()
    {
        return view('admin.student-distribution.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'district' => 'required|string|max:100',
            'student_count' => 'required|integer|min:0',
        ]);

        $distribution = StudentDistribution::create($validated);

        ActivityLog::log('create', "Menambah persebaran siswa: {$validated['district']} ({$validated['academic_year']})", StudentDistribution::class, $distribution->id);

        return redirect()->route('admin.student-distribution.index')->with('success', 'Data persebaran siswa berhasil ditambahkan.');
    }

    public function edit(StudentDistribution $studentDistribution)
    {
        return view('admin.student-distribution.edit', compact('studentDistribution'));
    }

    public function update(Request $request, StudentDistribution $studentDistribution)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'district' => 'required|string|max:100',
            'student_count' => 'required|integer|min:0',
        ]);

        $studentDistribution->update($validated);

        ActivityLog::log('update', "Memperbarui persebaran siswa: {$studentDistribution->district} ({$studentDistribution->academic_year})", StudentDistribution::class, $studentDistribution->id);

        return redirect()->route('admin.student-distribution.index')->with('success', 'Data persebaran siswa berhasil diperbarui.');
    }

    public function destroy(StudentDistribution $studentDistribution)
    {
        $district = $studentDistribution->district;
        $academicYear = $studentDistribution->academic_year;
        
        $studentDistribution->delete();

        ActivityLog::log('delete', "Menghapus persebaran siswa: {$district} ({$academicYear})", StudentDistribution::class, null);

        return redirect()->route('admin.student-distribution.index')->with('success', 'Data persebaran siswa berhasil dihapus.');
    }
}
