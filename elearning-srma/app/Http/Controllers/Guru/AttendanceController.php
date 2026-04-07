<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\ClassSubject;
use App\Models\EClass;
use Illuminate\Http\Request;

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
    public function show(AttendanceSession $session)
    {
        // Check if classSubject exists and teacher is authorized
        if (!$session->classSubject || $session->classSubject->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $session->load(['records.student', 'classSubject.eClass', 'classSubject.subject']);

        return view('guru.attendance.show', compact('session'));
    }

    /**
     * Close attendance session
     */
    public function close(AttendanceSession $session)
    {
        if (!$session->classSubject || $session->classSubject->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$session->isOpen()) {
            return back()->withErrors('Presensi sudah ditutup atau dibatalkan');
        }

        $session->update([
            'status' => 'closed',
            'closed_at' => now()->format('H:i'),
        ]);

        return back()->with('success', 'Presensi ditutup');
    }

    /**
     * Cancel attendance session
     */
    public function cancel(AttendanceSession $session)
    {
        if (!$session->classSubject || $session->classSubject->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $session->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Presensi dibatalkan');

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
