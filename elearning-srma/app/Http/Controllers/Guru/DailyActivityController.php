<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\EClass;
use App\Models\Schedule;
use App\Models\ScheduleActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyActivityController extends Controller
{
    /**
     * Form input aktivitas harian per schedule untuk sebuah kelas + tanggal.
     */
    public function create(Request $request)
    {
        $teacherId = (int) auth()->id();

        // Dropdown kelas: kelas unik yang diajar guru (berdasarkan class_subjects)
        $classes = EClass::query()
            ->whereHas('classSubjects', fn ($q) => $q->where('teacher_id', $teacherId))
            ->orderBy('name')
            ->get();

        $classId = $request->integer('e_class_id');
        $date = $request->input('date', now()->toDateString());

        // Jika belum pilih kelas, tampilkan halaman selector saja
        if (! $classId) {
            return view('guru.daily-activities.create', [
                'classes' => $classes,
                'classId' => null,
                'date' => $date,
                'dayOfWeek' => null,
                'schedules' => collect(),
                'students' => collect(),
                'existing' => collect(),
                'attendanceSessions' => collect(),
            ]);
        }

        // Validate class input only after selected
        $request->validate([
            'e_class_id' => 'required|exists:e_classes,id',
            'date' => 'required|date',
        ]);

        // schedules untuk kelas pada hari itu, hanya yang diajar guru ini
        $dayOfWeek = strtolower(now()->parse($date)->format('l'));

        $schedules = Schedule::query()
            ->where('e_class_id', $classId)
            ->where('day_of_week', $dayOfWeek)
            ->whereHas('classSubject', fn ($q) => $q->where('teacher_id', $teacherId))
            ->with(['classSubject.subject', 'classSubject.eClass.students' => fn ($q) => $q->orderBy('name')])
            ->orderBy('start_time')
            ->get();

        // Jika tidak ada jadwal, JANGAN redirect ke URL yang sama (bisa menimbulkan redirect loop).
        // Kembalikan view create dan tampilkan pesan ramah.
        if ($schedules->isEmpty()) {
            // Still attempt to load class students and any attendance sessions for the
            // selected class/date so teachers can view sessions opened outside a
            // scheduled slot (fallback behavior). Only show the "no schedule" error
            // if there truly are no attendance sessions for that class/date.
            $classModel = EClass::with(['students' => fn($q) => $q->orderBy('name')])->find($classId);
            $students = $classModel ? $classModel->students->values() : collect();

            $attendanceSessions = AttendanceSession::query()
                ->whereDate('attendance_date', $date)
                ->whereHas('classSubject', fn($q) => $q->where('e_class_id', $classId))
                ->with(['records', 'classSubject'])
                ->get()
                ->keyBy('class_subject_id');

            $attendanceSessionsByClass = $attendanceSessions->filter(fn($s) => $s->classSubject)
                ->groupBy(fn($s) => $s->classSubject->e_class_id)
                ->map(fn($group) => $group->sortByDesc(fn($s) => $s->created_at));

            // If there are no sessions either, show the friendly error as before.
            if ($attendanceSessions->isEmpty()) {
                $request->session()->flash('error', 'Tidak ada jadwal untuk kelas & tanggal yang dipilih (atau Anda tidak mengajar jadwal tersebut).');

                return view('guru.daily-activities.create', [
                    'classes' => $classes,
                    'classId' => $classId,
                    'date' => $date,
                    'dayOfWeek' => $dayOfWeek,
                    'schedules' => collect(),
                    'students' => $students,
                    'existing' => collect(),
                    'attendanceSessions' => $attendanceSessions,
                    'attendanceSessionsByClass' => $attendanceSessionsByClass,
                ]);
            }

            // There are attendance sessions for this class/date even though no
            // schedules exist for that day — render view without flashing error so
            // teacher can inspect and use the session data.
            return view('guru.daily-activities.create', [
                'classes' => $classes,
                'classId' => $classId,
                'date' => $date,
                'dayOfWeek' => $dayOfWeek,
                'schedules' => collect(),
                'students' => $students,
                'existing' => collect(),
                'attendanceSessions' => $attendanceSessions,
                'attendanceSessionsByClass' => $attendanceSessionsByClass,
            ]);
        }

        // Ambil siswa dari kelas (pakai schedule pertama; semua schedule di kelas yg sama)
        $class = $schedules->first()->classSubject->eClass;
        $students = $class->students->sortBy('name')->values();

        // existing activities
        $existing = ScheduleActivity::query()
            ->whereDate('activity_date', $date)
            ->whereIn('schedule_id', $schedules->pluck('id'))
            ->get()
            ->keyBy(fn ($a) => $a->schedule_id . ':' . $a->student_id);

        // Attendance sessions for the selected class on the date. We intentionally query by
        // classSubject.e_class_id so sessions opened for any class_subject of this class
        // are included — this covers cases where a teacher opened presensi outside the
        // scheduled class_subject. Key by class_subject_id for direct lookup
        // and also prepare a grouping by e_class_id for fallback display.
        $attendanceSessions = AttendanceSession::query()
            ->whereDate('attendance_date', $date)
            ->whereHas('classSubject', fn($q) => $q->where('e_class_id', $classId))
            ->with(['records', 'classSubject'])
            ->get()
            ->keyBy('class_subject_id');

        // Group sessions by e_class_id and sort each group by creation time desc so
        // fallback picks the most recently opened session for the class.
        $attendanceSessionsByClass = $attendanceSessions->filter(fn($s) => $s->classSubject)
            ->groupBy(fn($s) => $s->classSubject->e_class_id)
            ->map(fn($group) => $group->sortByDesc(fn($s) => $s->created_at));

        return view('guru.daily-activities.create', compact(
            'classes',
            'classId',
            'date',
            'dayOfWeek',
            'schedules',
            'students',
            'existing',
            'attendanceSessions',
            'attendanceSessionsByClass'
        ));
    }

    /**
     * Simpan aktivitas harian: score + notes per student per schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'entries' => 'required|array',
            'entries.*.schedule_id' => 'required|exists:schedules,id',
            'entries.*.student_id' => 'required|exists:users,id',
            'entries.*.attendance_session_id' => 'nullable|exists:attendance_sessions,id',
            'entries.*.score' => 'nullable|integer|min:0|max:100',
            'entries.*.notes' => 'nullable|string',
        ]);

        $teacherId = (int) auth()->id();
        $date = $validated['date'];

        $scheduleIds = collect($validated['entries'])->pluck('schedule_id')->unique()->values();
        $allowed = Schedule::query()
            ->whereIn('id', $scheduleIds)
            ->whereHas('classSubject', fn ($q) => $q->where('teacher_id', $teacherId))
            ->count();

        abort_if($allowed !== $scheduleIds->count(), 403, 'Unauthorized');

        DB::transaction(function () use ($validated, $teacherId, $date) {
            foreach ($validated['entries'] as $e) {
                $hasContent = isset($e['score']) || (isset($e['notes']) && trim((string) $e['notes']) !== '');
                if (! $hasContent) {
                    continue;
                }

                ScheduleActivity::updateOrCreate(
                    [
                        'schedule_id' => (int) $e['schedule_id'],
                        'student_id' => (int) $e['student_id'],
                        'activity_date' => $date,
                    ],
                    [
                        'attendance_session_id' => isset($e['attendance_session_id']) && $e['attendance_session_id'] !== '' ? (int) $e['attendance_session_id'] : null,
                        'score' => $e['score'] ?? null,
                        'notes' => $e['notes'] ?? null,
                        'created_by' => $teacherId,
                    ]
                );
            }
        });

        // Redirect back to the selected class/date view
        $firstScheduleId = $scheduleIds->first();
        $classId = $firstScheduleId
            ? (int) (Schedule::whereKey($firstScheduleId)->value('e_class_id') ?? 0)
            : 0;

        return redirect()
            ->route('guru.daily-activities.create', ['e_class_id' => $classId ?: null, 'date' => $date])
            ->with('success', 'Aktivitas harian tersimpan.');
    }
}
