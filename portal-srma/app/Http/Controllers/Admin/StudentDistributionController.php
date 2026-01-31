<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentDistribution;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentDistributionController extends Controller
{
    public function index(Request $request)
    {
        // Hitung tahun ajaran aktif
        $year = date('Y');
        $month = date('n');
        $currentAcademicYear = $month >= 7 ? $year . '/' . ($year + 1) : ($year - 1) . '/' . $year;
        
        // Filter berdasarkan tahun ajaran (default: tahun aktif)
        $selectedYear = $request->get('academic_year', $currentAcademicYear);
        
        // Ambil semua tahun ajaran yang tersedia
        $academicYears = StudentDistribution::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');
        
        // Hitung statistik
        $stats = null;
        
        if ($selectedYear === 'all') {
            // Mode "Semua Tahun": Kelompokkan berdasarkan wilayah
            $distributions = StudentDistribution::select('district')
                ->selectRaw('SUM(student_count) as total_students')
                ->selectRaw('COUNT(DISTINCT academic_year) as year_count')
                ->groupBy('district')
                ->orderBy('total_students', 'desc')
                ->paginate(30)
                ->appends(['academic_year' => $selectedYear]);
            
            // Ambil detail per wilayah untuk dropdown
            $districtDetails = StudentDistribution::orderBy('district')
                ->orderBy('academic_year', 'desc')
                ->get()
                ->groupBy('district');
            
            // Statistik keseluruhan
            $stats = [
                'total_students' => StudentDistribution::sum('student_count'),
                'total_districts' => StudentDistribution::distinct('district')->count('district'),
            ];
            
            return view('admin.student-distribution.index', compact(
                'distributions', 
                'academicYears', 
                'currentAcademicYear', 
                'selectedYear', 
                'stats',
                'districtDetails'
            ));
        } else {
            // Mode tahun tertentu: Tampilkan seperti biasa
            $distributions = StudentDistribution::where('academic_year', $selectedYear)
                ->orderBy('student_count', 'desc')
                ->paginate(30)
                ->appends(['academic_year' => $selectedYear]);
            
            $yearData = StudentDistribution::where('academic_year', $selectedYear)->get();
            $stats = [
                'total_students' => $yearData->sum('student_count'),
                'total_districts' => $yearData->count(),
            ];
            
            return view('admin.student-distribution.index', compact(
                'distributions', 
                'academicYears', 
                'currentAcademicYear', 
                'selectedYear', 
                'stats'
            ));
        }
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

    public function edit(StudentDistribution $student_distribution)
    {
        $studentDistribution = $student_distribution;
        return view('admin.student-distribution.edit', compact('studentDistribution'));
    }

    public function update(Request $request, StudentDistribution $student_distribution)
    {
        $studentDistribution = $student_distribution;
        $validated = $request->validate([
            'district' => 'required|string|max:100',
            'student_count' => 'required|integer|min:0',
        ]);

        $studentDistribution->update($validated);

        ActivityLog::log('update', "Memperbarui persebaran siswa: {$studentDistribution->district} ({$studentDistribution->academic_year})", StudentDistribution::class, $studentDistribution->id);

        return redirect()->route('admin.student-distribution.index')->with('success', 'Data persebaran siswa berhasil diperbarui.');
    }

    public function destroy(StudentDistribution $student_distribution)
    {
        $studentDistribution = $student_distribution;
        $district = $studentDistribution->district;
        $academicYear = $studentDistribution->academic_year;
        
        $studentDistribution->delete();

        ActivityLog::log('delete', "Menghapus persebaran siswa: {$district} ({$academicYear})", StudentDistribution::class, null);

        return redirect()->route('admin.student-distribution.index')->with('success', 'Data persebaran siswa berhasil dihapus.');
    }
}
