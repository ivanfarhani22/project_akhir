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

        Log::info('HIT guru.attendance.close', [
            'auth_id' => $teacherId,
            'session_id' => $attendance->id,
            'opened_by' => $attendance->opened_by,
            'class_subject_id' => $attendance->class_subject_id,
            'has_class_subject' => (bool) $attendance->classSubject,
            'subject_teacher_id' => $attendance->classSubject?->teacher_id,
            'path' => request()->path(),
            'method' => request()->method(),
            'route_params' => request()->route()?->parameters(),
        ]);

        // Allow closing if teacher is the subject teacher OR the one who opened this session.
        // Do not hard-fail when classSubject relation is missing (data drift / eager load issues).
        $subjectTeacherId = $attendance->classSubject?->teacher_id;
        if ($subjectTeacherId !== $teacherId && (int) $attendance->opened_by !== (int) $teacherId) {
            Log::warning('DENY guru.attendance.close', [
                'auth_id' => $teacherId,
                'session_id' => $attendance->id,
                'opened_by' => $attendance->opened_by,
                'subject_teacher_id' => $subjectTeacherId,
            ]);
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
        if ($subjectTeacherId !== $teacherId && (int) $attendance->opened_by !== (int) $teacherId) {
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

    /**
     * Manual attendance input (like admin), but restricted to teacher's own class_subject.
     */
    public function createManual()
    {
        $classSubjects = ClassSubject::where('teacher_id', auth()->id())
            ->with('eClass.students', 'subject')
            ->orderBy('e_class_id')
            ->get();

        if ($classSubjects->isEmpty()) {
            return back()->withErrors('Anda tidak mengajar mata pelajaran apapun');
        }

        return view('guru.attendance.manual-create', compact('classSubjects'));
    }

    /**
     * Store manual attendance session + records (like admin).
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subjects,id',
            'attendance_date'  => 'required|date',
            'attendance'       => 'required|array',
            'attendance.*'     => 'required|in:present,absent,late,sick,excused',
            'notes'            => 'nullable|string',
        ]);

        $classSubject = ClassSubject::with('eClass.students', 'subject', 'eClass')
            ->findOrFail($validated['class_subject_id']);

        abort_if((int) $classSubject->teacher_id !== (int) auth()->id(), 403, 'Unauthorized');

        $existing = AttendanceSession::where('class_subject_id', $classSubject->id)
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->first();

        if ($existing) {
            return redirect()
                ->route('guru.attendance.manual.edit', $existing)
                ->with('error', 'Session presensi untuk mapel & tanggal ini sudah ada. Silakan edit.');
        }

        $session = AttendanceSession::create([
            'class_subject_id' => $classSubject->id,
            'attendance_date'  => $validated['attendance_date'],
            'notes'            => $validated['notes'] ?? null,
            'opened_by'        => auth()->id(),
            // For manual input, mark as closed immediately.
            'status'           => 'closed',
            'opened_at'        => now()->format('H:i:s'),
            'closed_at'        => now()->format('H:i:s'),
        ]);

        foreach ($validated['attendance'] as $studentId => $status) {
            // Ensure the student is in the class.
            abort_if(! $classSubject->eClass->students->contains('id', (int) $studentId), 422, 'Siswa tidak valid untuk kelas ini');

            AttendanceRecord::create([
                'attendance_session_id' => $session->id,
                'student_id'            => $studentId,
                'status'                => $status,
                'checked_in_at'         => now(),
            ]);
        }

        return redirect()
            ->route('guru.attendance.show', $session)
            ->with('success', 'Presensi manual berhasil dicatat.');
    }

    /**
     * Edit manual attendance (update records of existing session).
     */
    public function editManual(AttendanceSession $attendance)
    {
        $attendance->load(['classSubject.eClass.students', 'classSubject.subject', 'records.student']);

        abort_if(! $attendance->classSubject, 404);
        abort_if((int) $attendance->classSubject->teacher_id !== (int) auth()->id(), 403, 'Unauthorized');

        return view('guru.attendance.manual-edit', ['session' => $attendance]);
    }

    /**
     * Update manual attendance records.
     */
    public function updateManual(Request $request, AttendanceSession $attendance)
    {
        $validated = $request->validate([
            'attendance'       => 'required|array',
            'attendance.*'     => 'required|in:present,absent,late,sick,excused',
            'notes'            => 'nullable|string',
        ]);

        $attendance->load(['classSubject.eClass.students']);

        abort_if(! $attendance->classSubject, 404);
        abort_if((int) $attendance->classSubject->teacher_id !== (int) auth()->id(), 403, 'Unauthorized');

        if ($request->has('notes')) {
            $attendance->update(['notes' => $validated['notes'] ?? null]);
        }

        foreach ($validated['attendance'] as $studentId => $status) {
            abort_if(! $attendance->classSubject->eClass->students->contains('id', (int) $studentId), 422, 'Siswa tidak valid untuk kelas ini');

            AttendanceRecord::updateOrCreate(
                [
                    'attendance_session_id' => $attendance->id,
                    'student_id'            => $studentId,
                ],
                [
                    'status'        => $status,
                    'checked_in_at' => now(),
                ]
            );
        }

        // Force closed for manual edit flow.
        $attendance->update([
            'status'    => 'closed',
            'closed_at' => $attendance->closed_at ?: now()->format('H:i:s'),
        ]);

        return redirect()
            ->route('guru.attendance.show', $attendance)
            ->with('success', 'Presensi manual berhasil diperbarui.');
    }
}
