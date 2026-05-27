<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use App\Models\ScheduleActivity;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        [$children, $studentId, $student, $date, $rows] = $this->buildReportData($request);

        return view('orang-tua.daily-reports.index', compact('children', 'studentId', 'student', 'date', 'rows'));
    }

    public function pdf(Request $request)
    {
        [$children, $studentId, $student, $date, $rows] = $this->buildReportData($request);

        abort_if(! $studentId, 422, 'Pilih anak terlebih dahulu.');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orang-tua.daily-reports.pdf', [
            'children'  => $children,
            'studentId' => $studentId,
            'student'   => $student,
            'date'      => $date,
            'rows'      => $rows,
        ])->setPaper('a4', 'portrait');

        $safeName = preg_replace('/[^a-zA-Z0-9\-_]+/', '-', (string) ($student?->name ?? 'siswa'));
        $fileName = 'laporan-harian-' . $safeName . '-' . $date . '.pdf';

        return $pdf->download($fileName);
    }

    private function buildReportData(Request $request): array
    {
        $parent = auth()->user();

        $children = $parent->children()
            ->where('role', 'siswa')
            ->orderBy('name')
            ->get();

        $studentId = $request->integer('student_id') ?: ($children->count() === 1 ? $children->first()->id : null);
        $date = $request->input('date', now()->toDateString());

        $rows = collect();
        $student = null;

        if ($studentId) {
            abort_if(! $children->contains('id', $studentId), 403);

            $student = $children->firstWhere('id', $studentId);

            // Ambil kelas siswa (asumsi 1 kelas utama yang dipakai untuk jadwal)
            $classId = $student->classes()->pluck('e_classes.id')->first();

            if ($classId) {
                $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));

                $schedules = Schedule::query()
                    ->where('e_class_id', $classId)
                    ->where('day_of_week', $dayOfWeek)
                    ->with(['classSubject.subject'])
                    ->orderBy('start_time')
                    ->get();

                // Fallback: kalau jadwal kosong, tapi ada activity di tanggal tsb,
                // tetap tampilkan baris berdasarkan schedule_id yg tersimpan.
                if ($schedules->isEmpty()) {
                    $scheduleIdsFromActivities = ScheduleActivity::query()
                        ->where('student_id', $studentId)
                        ->whereDate('activity_date', $date)
                        ->pluck('schedule_id')
                        ->unique()
                        ->values();

                    if ($scheduleIdsFromActivities->isNotEmpty()) {
                        $schedules = Schedule::query()
                            ->whereIn('id', $scheduleIdsFromActivities)
                            ->with(['classSubject.subject'])
                            ->orderBy('start_time')
                            ->get();
                    }
                }

                $activities = ScheduleActivity::query()
                    ->where('student_id', $studentId)
                    ->whereDate('activity_date', $date)
                    ->whereIn('schedule_id', $schedules->pluck('id'))
                    ->get()
                    ->keyBy('schedule_id');

                $attendanceSessions = AttendanceSession::query()
                    ->whereDate('attendance_date', $date)
                    ->whereIn('class_subject_id', $schedules->pluck('class_subject_id'))
                    ->with(['records' => fn ($q) => $q->where('student_id', $studentId)])
                    ->get()
                    ->keyBy('class_subject_id');

                $rows = $schedules->values()->map(function ($sch, $idx) use ($activities, $attendanceSessions) {
                    $activity = $activities->get($sch->id);

                    $session = $attendanceSessions->get($sch->class_subject_id);
                    $record = $session?->records?->first();

                    $presensi = $record?->status;

                    $presensiLabel = match ($presensi) {
                        'present' => 'Hadir',
                        'late' => 'Terlambat',
                        'excused' => 'Izin',
                        'absent' => 'Tidak Hadir',
                        default => '-',
                    };

                    return [
                        'no' => $idx + 1,
                        'kegiatan' => $sch->classSubject?->subject?->name ?? '-',
                        'presensi' => $presensiLabel,
                        'nilai' => $activity?->score,
                        'catatan' => $activity?->notes,
                    ];
                });
            }
        }

        return [$children, $studentId, $student, $date, $rows];
    }
}
