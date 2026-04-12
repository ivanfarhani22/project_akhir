<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\ClassSubject;
use App\Models\EClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display attendance sessions - all for this teacher
     */
    public function index()
    {
        // Get all class subjects taught by this teacher
        $classSubjects = ClassSubject::where('teacher_id', auth()->id())
            ->with('eClass', 'subject')
            ->get();
        
        // Get all attendance sessions for these subjects
        $sessions = AttendanceSession::whereIn('class_subject_id', $classSubjects->pluck('id'))
            ->with('classSubject.eClass', 'classSubject.subject', 'records.student')
            ->orderBy('attendance_date', 'desc')
            ->orderBy('opened_at', 'desc')
            ->get();

        return view('guru.attendance.index-all', compact('classSubjects', 'sessions'));
    }

    /**
     * Open attendance session (create)
     */
    public function create()
    {
        // Get all class subjects taught by this teacher
        $classSubjects = ClassSubject::where('teacher_id', auth()->id())
            ->with('eClass', 'subject')
            ->get();
        
        if ($classSubjects->isEmpty()) {
            return back()->withErrors('Anda tidak mengajar mata pelajaran apapun');
        }

        return view('guru.attendance.create', compact('classSubjects'));
    }

    /**
     * Store new attendance session
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subjects,id',
            'attendance_date' => 'required|date',
            'opened_at' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $classSubject = ClassSubject::findOrFail($validated['class_subject_id']);
        
        // Verify teacher is authorized for this subject
        if ($classSubject->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if attendance already exists for this date
        $existing = AttendanceSession::where('class_subject_id', $validated['class_subject_id'])
            ->where('attendance_date', $validated['attendance_date'])
            ->first();

        if ($existing) {
            return back()->withErrors('Presensi untuk mata pelajaran ini pada tanggal ini sudah ada')->withInput();
        }

        // Create attendance session
        $session = AttendanceSession::create([
            'class_subject_id' => $validated['class_subject_id'],
            'opened_by' => auth()->id(),
            'attendance_date' => $validated['attendance_date'],
            'opened_at' => $validated['opened_at'],
            'status' => 'open',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create attendance records for all students in class
        $students = $classSubject->eClass->students;
        foreach ($students as $student) {
            AttendanceRecord::create([
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
                'status' => 'absent',
            ]);
        }

        return redirect()
            ->route('guru.attendance.show', $session)
            ->with('success', 'Presensi dibuka. Siswa dapat melakukan absensi sekarang.');
    }

    /**
     * Show attendance session details
     */
    public function show(AttendanceSession $attendance)
    {
        $route = request()->route();
        $routeName = $route?->getName();
        $routeUri = method_exists($route, 'uri') ? $route->uri() : null;
        $routeParams = $route?->parameters() ?? null;

        Log::info('HIT guru.attendance.show', [
            'auth_id' => auth()->id(),
            'session_id' => $attendance->id,
            'opened_by' => $attendance->opened_by,
            'class_subject_id' => $attendance->class_subject_id,
            'path' => request()->path(),
            'route_name' => $routeName,
            'route_uri' => $routeUri,
            'route_params' => $routeParams,
        ]);

        $teacherId = auth()->id();

        // If relation is missing (data drift), still allow the teacher who opened the session to VIEW it.
        // This prevents false 403 due to class_subject relation resolution issues.
        if (!$attendance->classSubject) {
            if ((int) $attendance->opened_by !== (int) $teacherId) {
                abort(403, 'Unauthorized');
            }

            // Load only what doesn't depend on classSubject to avoid null access in view
            $attendance->load(['records.student']);

            return view('guru.attendance.show', ['session' => $attendance]);
        }

        // Normal authorization: teacher for the subject OR opened_by
        $subjectTeacherId = $attendance->classSubject?->teacher_id;
        if ($subjectTeacherId !== $teacherId && (int) $attendance->opened_by !== (int) $teacherId) {
            abort(403, 'Unauthorized');
        }

        $attendance->load(['records.student', 'classSubject.eClass', 'classSubject.subject']);

        return view('guru.attendance.show', ['session' => $attendance]);
    }

    /**
     * Close attendance session
     */
    public function close(AttendanceSession $attendance)
    {
        $teacherId = auth()->id();
        $subjectTeacherId = $attendance->classSubject?->teacher_id;

        if (!$attendance->classSubject || ($subjectTeacherId !== $teacherId && (int)$attendance->opened_by !== (int)$teacherId)) {
            abort(403, 'Unauthorized');
        }

        if (!$attendance->isOpen()) {
            return back()->withErrors('Presensi sudah ditutup atau dibatalkan');
        }

        $attendance->update([
            'status' => 'closed',
            'closed_at' => now()->format('H:i'),
        ]);

        return back()->with('success', 'Presensi ditutup');
    }

    /**
     * Cancel attendance session
     */
    public function cancel(AttendanceSession $attendance)
    {
        $teacherId = auth()->id();
        $subjectTeacherId = $attendance->classSubject?->teacher_id;

        if (!$attendance->classSubject || ($subjectTeacherId !== $teacherId && (int)$attendance->opened_by !== (int)$teacherId)) {
            abort(403, 'Unauthorized');
        }

        $attendance->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Presensi dibatalkan');
    }

    /**
     * Guru tidak bisa edit/delete, hanya admin
     */
    public function edit()
    {
        abort(403, 'Only admin can edit attendance');
    }

    public function update(Request $request)
    {
        abort(403, 'Only admin can edit attendance');
    }

    public function destroy()
    {
        abort(403, 'Only admin can delete attendance');
    }
}
