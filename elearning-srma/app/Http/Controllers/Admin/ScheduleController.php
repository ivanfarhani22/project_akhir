<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\Schedule;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Tampilkan form create schedule
     */
    public function create(EClass $class)
    {
        // Hanya ambil class subjects yang ada
        $classSubjects = $class->classSubjects()->with('subject', 'teacher')->get();
        
        if ($classSubjects->isEmpty()) {
            return redirect()->route('admin.classes.show', $class)
                            ->with('error', 'Tambahkan mata pelajaran terlebih dahulu sebelum membuat jadwal.');
        }

        return view('admin.schedules.create', compact('class', 'classSubjects'));
    }

    /**
     * Simpan schedule baru
     */
    public function store(Request $request, EClass $class)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subjects,id',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        // Verifikasi class_subject belongs to class
        $classSubject = ClassSubject::where('id', $validated['class_subject_id'])
                                   ->where('e_class_id', $class->id)
                                   ->firstOrFail();

        $validated['e_class_id'] = $class->id;

        Schedule::create($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_schedule',
            'description' => "Admin membuat jadwal untuk kelas {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)
                        ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit schedule
     */
    public function edit(EClass $class, Schedule $schedule)
    {
        if ($schedule->e_class_id != $class->id) {
            abort(404);
        }

        $classSubjects = $class->classSubjects()->with('subject', 'teacher')->get();

        return view('admin.schedules.edit', compact('class', 'schedule', 'classSubjects'));
    }

    /**
     * Update schedule
     */
    public function update(Request $request, EClass $class, Schedule $schedule)
    {
        if ($schedule->e_class_id != $class->id) {
            abort(404);
        }

        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subjects,id',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        // Verifikasi class_subject belongs to class
        $classSubject = ClassSubject::where('id', $validated['class_subject_id'])
                                   ->where('e_class_id', $class->id)
                                   ->firstOrFail();

        $schedule->update($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_schedule',
            'description' => "Admin update jadwal kelas {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)
                        ->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Hapus schedule
     */
    public function destroy(Request $request, EClass $class, Schedule $schedule)
    {
        if ($schedule->e_class_id != $class->id) {
            abort(404);
        }

        $schedule->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_schedule',
            'description' => "Admin menghapus jadwal dari kelas {$class->name}",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('admin.classes.show', $class)
                        ->with('success', 'Jadwal berhasil dihapus!');
    }
}
