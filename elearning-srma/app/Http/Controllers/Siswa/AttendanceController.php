<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Get open attendance sessions for a class subject
     */
    public function show(ClassSubject $classSubject)
    {
        $class = $classSubject->eClass;
        
        // Check if student is registered in this class
        abort_if(!auth()->user()->classes->contains($class), 403);

        // Get the attendance session for today for this subject (if any)
        $session = AttendanceSession::where('class_subject_id', $classSubject->id)
            ->where('status', 'open')
            ->where('attendance_date', today())
            ->with('records.student')
            ->first();

        $hasAttended = false;
        if ($session) {
            // Check if student has already done attendance
            $record = $session->records()
                ->where('student_id', auth()->id())
                ->first();
            $hasAttended = $record && $record->status !== 'absent';
        }

        return view('siswa.attendance.show', compact('classSubject', 'class', 'session', 'hasAttended'));
    }

    /**
     * Student performs attendance
     */
    public function store(Request $request, AttendanceSession $session)
    {
        $class = $session->classSubject->eClass;
        
        // Check if student is in this class
        abort_if(!auth()->user()->classes->contains($class), 403);

        // Check if attendance session is still open
        if (!$session->isOpen()) {
            return back()->withErrors('Presensi sudah ditutup atau dibatalkan');
        }

        // Find or create attendance record
        $record = $session->records()
            ->where('student_id', auth()->id())
            ->first();

        if (!$record) {
            abort(404, 'Attendance record not found');
        }

        // Update attendance record
        $record->update([
            'status' => 'present',
            'checked_in_at' => now(),
        ]);

        return back()->with('success', 'Absensi berhasil dicatat. Terima kasih!');
    }
}

