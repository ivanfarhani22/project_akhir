<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\EClass;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkScheduleController extends Controller
{
    /**
     * Halaman bulk scheduling (interactive).
     */
    public function edit(Request $request, EClass $class)
    {
        $class->load(['classSubjects.subject', 'classSubjects.teacher', 'schedules.classSubject.subject', 'schedules.classSubject.teacher']);

        $classSubjects = $class->classSubjects()->with(['subject', 'teacher'])->orderBy('id')->get();
        $teachers = User::query()->where('role', 'guru')->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.schedules.bulk', [
            'class' => $class,
            'classSubjects' => $classSubjects,
            'teachers' => $teachers,
            'existingSchedules' => $class->schedules,
        ]);
    }

    /**
     * Simpan jadwal secara massal.
     *
     * Default mode: replace_day (profesional untuk bulk edit).
     */
    public function store(Request $request, EClass $class)
    {
        $validated = $request->validate([
            'mode' => ['nullable', 'in:replace_day,merge'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.class_subject_id' => ['required', 'integer', 'exists:class_subjects,id'],
            'items.*.day_of_week' => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'items.*.start_time' => ['required', 'date_format:H:i'],
            'items.*.end_time' => ['required', 'date_format:H:i'],
            'items.*.room' => ['nullable', 'string', 'max:100'],
            'items.*.notes' => ['nullable', 'string'],
        ]);

        $mode = $validated['mode'] ?? 'replace_day';
        $items = collect($validated['items']);

        // Ensure all class_subject_id belong to this class
        $classSubjectIds = $items->pluck('class_subject_id')->unique()->values();
        $validCount = ClassSubject::query()
            ->where('e_class_id', $class->id)
            ->whereIn('id', $classSubjectIds)
            ->count();

        if ($validCount !== $classSubjectIds->count()) {
            return back()->withErrors([
                'items' => 'Ada mata pelajaran yang tidak valid untuk kelas ini.'
            ])->withInput();
        }

        // Basic time validation
        foreach ($items as $idx => $it) {
            if ($it['end_time'] <= $it['start_time']) {
                return back()->withErrors([
                    "items.$idx.end_time" => 'Jam selesai harus lebih besar dari jam mulai.'
                ])->withInput();
            }
        }

        // Conflict checks (minimal): within submitted items (same class/day) cannot overlap
        $byDay = $items->groupBy('day_of_week');
        foreach ($byDay as $day => $dayItems) {
            $sorted = $dayItems->sortBy('start_time')->values();
            for ($i = 0; $i < $sorted->count() - 1; $i++) {
                $a = $sorted[$i];
                $b = $sorted[$i + 1];
                if ($a['end_time'] > $b['start_time']) {
                    return back()->withErrors([
                        'items' => "Bentrok jam pada hari {$day}. Pastikan slot tidak overlap."
                    ])->withInput();
                }
            }
        }

        DB::transaction(function () use ($class, $mode, $items) {
            if ($mode === 'replace_day') {
                $days = $items->pluck('day_of_week')->unique()->values();
                Schedule::query()
                    ->where('e_class_id', $class->id)
                    ->whereIn('day_of_week', $days)
                    ->delete();
            }

            foreach ($items as $it) {
                // Unique index: (e_class_id, day_of_week, start_time, end_time)
                // - merge: update jika slot sudah ada
                // - replace_day: biasanya sudah terhapus, tapi tetap aman
                Schedule::updateOrCreate(
                    [
                        'e_class_id' => $class->id,
                        'day_of_week' => $it['day_of_week'],
                        'start_time' => $it['start_time'],
                        'end_time' => $it['end_time'],
                    ],
                    [
                        'class_subject_id' => $it['class_subject_id'],
                        'room' => $it['room'] ?? null,
                        'notes' => $it['notes'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.classes.show', $class)
            ->with('success', 'Bulk scheduling berhasil disimpan.');
    }
}
