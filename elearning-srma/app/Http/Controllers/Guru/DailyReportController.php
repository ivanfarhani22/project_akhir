<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\User;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = auth()->id();

        // Students from classes taught by this teacher
        $studentIds = \App\Models\ClassSubject::where('teacher_id', $teacherId)
            ->with('eClass.students:id,name,role')
            ->get()
            ->pluck('eClass.students')
            ->flatten()
            ->where('role', 'siswa')
            ->pluck('id')
            ->unique()
            ->values();

        $query = DailyReport::query()->with('student')->whereIn('student_id', $studentIds);

        if ($request->filled('student_id')) {
            $query->where('student_id', (int) $request->student_id);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('report_date', [$request->from, $request->to]);
        }

        $reports = $query->orderByDesc('report_date')->paginate(20);

        $students = User::whereIn('id', $studentIds)->orderBy('name')->get();

        return view('guru.daily-reports.index', compact('reports', 'students'));
    }

    public function create(Request $request)
    {
        $teacherId = auth()->id();

        $studentIds = \App\Models\ClassSubject::where('teacher_id', $teacherId)
            ->with('eClass.students:id,name,role')
            ->get()
            ->pluck('eClass.students')
            ->flatten()
            ->where('role', 'siswa')
            ->pluck('id')
            ->unique()
            ->values();

        $students = User::whereIn('id', $studentIds)->orderBy('name')->get();

        return view('guru.daily-reports.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'  => 'required|exists:users,id',
            'report_date' => 'required|date',
            'notes'       => 'nullable|string',
        ]);

        $teacherId = auth()->id();

        // Authorize: student must be in a class taught by this teacher.
        $allowedStudentIds = \App\Models\ClassSubject::where('teacher_id', $teacherId)
            ->with('eClass.students:id')
            ->get()
            ->pluck('eClass.students')
            ->flatten()
            ->pluck('id')
            ->unique();

        abort_if(! $allowedStudentIds->contains((int) $validated['student_id']), 403);

        $student = User::findOrFail($validated['student_id']);

        // Snapshot nilai (avg grade) & presensi (present/total) for this student at creation time
        $avgGrade = (float) ($student->grades()->avg('score') ?? 0);

        $attendanceTotal = \App\Models\AttendanceRecord::where('student_id', $student->id)->count();
        $attendancePresent = \App\Models\AttendanceRecord::where('student_id', $student->id)->where('status', 'present')->count();

        $report = DailyReport::updateOrCreate(
            [
                'student_id' => $student->id,
                'report_date' => $validated['report_date'],
            ],
            [
                'created_by' => $teacherId,
                'created_by_role' => auth()->user()->role,
                'notes' => $validated['notes'] ?? null,
                'average_grade' => $avgGrade,
                'attendance_present' => $attendancePresent,
                'attendance_total' => $attendanceTotal,
            ]
        );

        return redirect()->route('guru.daily-reports.show', $report)->with('success', 'Jurnal harian tersimpan.');
    }

    public function show(DailyReport $dailyReport)
    {
        $teacherId = auth()->id();

        // Authorize via student membership in classes taught by teacher
        $allowedStudentIds = \App\Models\ClassSubject::where('teacher_id', $teacherId)
            ->with('eClass.students:id')
            ->get()
            ->pluck('eClass.students')
            ->flatten()
            ->pluck('id')
            ->unique();

        abort_if(! $allowedStudentIds->contains((int) $dailyReport->student_id), 403);

        $dailyReport->load(['student', 'author']);

        return view('guru.daily-reports.show', compact('dailyReport'));
    }

    public function edit(DailyReport $dailyReport)
    {
        // Only allow editing own-created reports (teacher) for safety.
        abort_if((int) $dailyReport->created_by !== (int) auth()->id(), 403);

        $dailyReport->load(['student']);

        return view('guru.daily-reports.edit', compact('dailyReport'));
    }

    public function update(Request $request, DailyReport $dailyReport)
    {
        abort_if((int) $dailyReport->created_by !== (int) auth()->id(), 403);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $dailyReport->update([
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('guru.daily-reports.show', $dailyReport)->with('success', 'Jurnal harian diperbarui.');
    }
}
