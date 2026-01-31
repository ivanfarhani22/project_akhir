<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentData;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentDataController extends Controller
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
        $academicYears = StudentData::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');
        
        // Hitung statistik
        $stats = null;
        
        if ($selectedYear === 'all') {
            // Mode "Semua Tahun": Kelompokkan berdasarkan tahun ajaran
            // Gunakan DB query untuk menghindari konflik dengan accessor model
            $studentData = \DB::table('student_data')
                ->select('academic_year')
                ->selectRaw('SUM(male_count) as total_male')
                ->selectRaw('SUM(female_count) as total_female')
                ->selectRaw('SUM(male_count + female_count) as total_students')
                ->selectRaw('SUM(study_groups) as total_rombel')
                ->selectRaw('COUNT(*) as class_count')
                ->groupBy('academic_year')
                ->orderBy('academic_year', 'desc')
                ->paginate(20)
                ->appends(['academic_year' => $selectedYear]);
            
            // Ambil detail per tahun untuk dropdown
            $yearDetails = StudentData::orderBy('academic_year', 'desc')
                ->orderBy('class_name')
                ->get()
                ->groupBy('academic_year');
            
            // Statistik keseluruhan
            $stats = [
                'total_male' => StudentData::sum('male_count'),
                'total_female' => StudentData::sum('female_count'),
                'total_students' => StudentData::sum('male_count') + StudentData::sum('female_count'),
                'total_rombel' => StudentData::sum('study_groups'),
            ];
            
            return view('admin.student-data.index', compact(
                'studentData', 
                'academicYears', 
                'currentAcademicYear', 
                'selectedYear', 
                'stats',
                'yearDetails'
            ));
        } else {
            // Mode tahun tertentu: Tampilkan seperti biasa
            $studentData = StudentData::where('academic_year', $selectedYear)
                ->orderBy('class_name')
                ->paginate(20)
                ->appends(['academic_year' => $selectedYear]);
            
            $yearData = StudentData::where('academic_year', $selectedYear)->get();
            $stats = [
                'total_male' => $yearData->sum('male_count'),
                'total_female' => $yearData->sum('female_count'),
                'total_students' => $yearData->sum('male_count') + $yearData->sum('female_count'),
                'total_rombel' => $yearData->sum('study_groups'),
            ];
            
            return view('admin.student-data.index', compact(
                'studentData', 
                'academicYears', 
                'currentAcademicYear', 
                'selectedYear', 
                'stats'
            ));
        }
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

    public function edit(StudentData $student_datum)
    {
        $studentData = $student_datum;
        return view('admin.student-data.edit', compact('studentData'));
    }

    public function update(Request $request, StudentData $student_datum)
    {
        $studentData = $student_datum;
        
        // Hanya validasi field yang bisa diedit (tahun ajaran dan kelas tidak bisa diubah)
        $validated = $request->validate([
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
            'study_groups' => 'required|integer|min:1',
        ]);

        $studentData->update($validated);

        ActivityLog::log('update', "Memperbarui data siswa: {$studentData->class_name} ({$studentData->academic_year})", StudentData::class, $studentData->id);

        return redirect()->route('admin.student-data.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(StudentData $student_datum)
    {
        $studentData = $student_datum;
        $className = $studentData->class_name;
        $academicYear = $studentData->academic_year;
        
        $studentData->delete();

        ActivityLog::log('delete', "Menghapus data siswa: {$className} ({$academicYear})", StudentData::class, null);

        return redirect()->route('admin.student-data.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
