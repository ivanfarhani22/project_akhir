<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display attendance sessions list.
     */
    public function index()
    {
        $classId = request('class');
        $search = request('search');
        
        $query = AttendanceSession::with(['classSubject' => fn($q) => $q->with(['eClass', 'subject', 'teacher'])]);
        
        if ($classId) {
            $query->whereHas('classSubject', fn($q) => $q->where('e_class_id', $classId));
        }
        
        if ($search) {
            $query->where('notes', 'like', "%$search%");
        }
        
        $sessions = $query->orderBy('created_at', 'desc')->paginate(20);
        $classes = EClass::orderBy('name')->get();
        
        // Calculate statistics
        $statistics = [
            'total_sessions' => AttendanceSession::count(),
            'average_attendance' => 0,
            'this_month' => AttendanceSession::whereMonth('created_at', now()->month)->count(),
            'total_students' => User::where('role', 'siswa')->count(),
        ];
        
        return view('admin.attendance.index', compact('sessions', 'classes', 'statistics'));
    }

    /**
     * Create new attendance session.
     */
    public function create()
    {
        $classes = EClass::with('students')->orderBy('name')->get();
        return view('admin.attendance.create', compact('classes'));
    }

    /**
     * Store new attendance session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subjects,id',
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        $classSubject = \App\Models\ClassSubject::find($validated['class_subject_id']);
        
        $session = AttendanceSession::create([
            'class_subject_id' => $validated['class_subject_id'],
            'attendance_date' => $validated['attendance_date'],
            'notes' => $validated['notes'],
            'opened_by' => auth()->id(),
            'opened_at' => now()->format('H:i:s'),
        ]);

        // Record attendance for each student
        foreach ($validated['attendance'] as $studentId => $status) {
            AttendanceRecord::create([
                'attendance_session_id' => $session->id,
                'student_id' => $studentId,
                'status' => $status,
            ]);
        }

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_attendance_session',
            'description' => "Admin buat session presensi di {$classSubject->subject->name} ({$classSubject->eClass->name}) pada " . $validated['attendance_date'],
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.attendance.show', $session)
                        ->with('success', 'Presensi berhasil dicatat!');
    }

    /**
     * Display attendance session details.
     */
    public function show(AttendanceSession $session)
    {
        $session->load(['classSubject' => fn($q) => $q->with(['eClass', 'subject', 'teacher'])]);
        
        $attendances = AttendanceRecord::where('attendance_session_id', $session->id)
            ->with('student')
            ->get();

        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
        ];

        return view('admin.attendance.show', compact('session', 'attendances', 'stats'));
    }

    /**
     * Show attendance by class.
     */
    public function byClass($classId)
    {
        $class = EClass::with('students')->findOrFail($classId);
        
        $sessions = AttendanceSession::where('e_class_id', $classId)
            ->orderBy('session_date', 'desc')
            ->get();

        // Calculate attendance percentage per student
        $studentAttendance = $class->students->map(function($student) {
            $attendances = Attendance::where('student_id', $student->id)
                ->whereHas('session', fn($q) => $q->where('e_class_id', $student->classes->first()?->pivot?->e_class_id))
                ->get();

            return [
                'student' => $student,
                'total' => $attendances->count(),
                'present' => $attendances->where('status', 'present')->count(),
                'percentage' => $attendances->count() > 0 
                    ? round(($attendances->where('status', 'present')->count() / $attendances->count()) * 100)
                    : 0,
            ];
        });

        return view('admin.attendance.by-class', compact('class', 'sessions', 'studentAttendance'));
    }

    /**
     * Show attendance by student.
     */
    public function byStudent($studentId)
    {
        $student = User::where('role', 'siswa')->findOrFail($studentId);
        
        $attendances = Attendance::where('student_id', $studentId)
            ->with('session.eClass')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($a) => $a->session->eClass->id);

        // Overall attendance percentage
        $allAttendances = Attendance::where('student_id', $studentId)->get();
        $attendancePercentage = $allAttendances->count() > 0
            ? round(($allAttendances->where('status', 'present')->count() / $allAttendances->count()) * 100)
            : 0;

        return view('admin.attendance.by-student', compact('student', 'attendances', 'attendancePercentage'));
    }

    /**
     * View attendance statistics.
     */
    public function statistics()
    {
        $classId = request('class_id');
        $from = request('from');
        $to = request('to');

        $query = AttendanceSession::with(['classSubject' => fn($q) => $q->with(['eClass', 'subject', 'teacher'])]);

        if ($classId) {
            $query->whereHas('classSubject', fn($q) => $q->where('e_class_id', $classId));
        }

        if ($from && $to) {
            $query->whereBetween('attendance_date', [$from, $to]);
        }

        $sessions = $query->get();
        $sessionIds = $sessions->pluck('id')->toArray();

        // Calculate statistics
        $totalRecords = AttendanceRecord::whereIn('attendance_session_id', $sessionIds)->count();
        $presentCount = AttendanceRecord::whereIn('attendance_session_id', $sessionIds)->where('status', 'present')->count();
        $absentCount = AttendanceRecord::whereIn('attendance_session_id', $sessionIds)->where('status', 'absent')->count();
        $lateCount = AttendanceRecord::whereIn('attendance_session_id', $sessionIds)->where('status', 'late')->count();
        $sickCount = AttendanceRecord::whereIn('attendance_session_id', $sessionIds)->where('status', 'sick')->count();

        $statistics = [
            'total_sessions' => $sessions->count(),
            'total_records' => $totalRecords,
            'present' => $presentCount,
            'absent' => $absentCount,
            'late' => $lateCount,
            'sick' => $sickCount,
            'attendance_rate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0,
            'by_class' => $sessions->groupBy(fn($s) => $s->classSubject->eClass->name)
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'total_students' => AttendanceRecord::whereIn('attendance_session_id', $group->pluck('id'))->count(),
                    'present' => AttendanceRecord::whereIn('attendance_session_id', $group->pluck('id'))->where('status', 'present')->count(),
                    'absent' => AttendanceRecord::whereIn('attendance_session_id', $group->pluck('id'))->where('status', 'absent')->count(),
                ]),
        ];

        $classes = EClass::orderBy('name')->get();

        return view('admin.attendance.statistics', compact('statistics', 'classes'));
    }

    /**
     * Export attendance report.
     */
    public function export(Request $request)
    {
        $classId = $request->query('class_id');
        $from = $request->query('from');
        $to = $request->query('to');

        $filename = "attendance_" . now()->format('Y-m-d_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($classId, $from, $to) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Tanggal', 'Kelas', 'Siswa', 'Status']);

            $query = Attendance::with('session.eClass', 'student');

            if ($classId) {
                $query->whereHas('session', fn($q) => $q->where('e_class_id', $classId));
            }

            if ($from && $to) {
                $query->whereHas('session', fn($q) => $q->whereBetween('session_date', [$from, $to]));
            }

            foreach ($query->get() as $attendance) {
                fputcsv($file, [
                    $attendance->session->session_date,
                    $attendance->session->eClass->name,
                    $attendance->student->name,
                    $attendance->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete attendance session.
     */
    public function destroy(AttendanceSession $session)
    {
        // Delete all attendance records for this session
        AttendanceRecord::where('attendance_session_id', $session->id)->delete();
        
        // Delete the session itself
        $session->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_attendance_session',
            'description' => "Admin hapus session presensi dari {$session->classSubject->subject->name}",
            'ip_address' => request()->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.attendance.index')
                        ->with('success', 'Presensi berhasil dihapus!');
    }
}
