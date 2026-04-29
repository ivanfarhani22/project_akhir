<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\AttendanceSession;

// ─────────────────────────────────────────────
// Misc / Public
// ─────────────────────────────────────────────

Route::view('/offline', 'offline.offline')->name('offline');

// ─────────────────────────────────────────────
// Auth
// ─────────────────────────────────────────────

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home → redirect by role
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return match (auth()->user()->role) {
        'admin_elearning' => redirect()->route('admin.dashboard'),
        'guru'            => redirect()->route('guru.dashboard'),
        default           => redirect()->route('siswa.dashboard'),
    };
})->name('home');

// ─────────────────────────────────────────────
// API (authenticated)
// ─────────────────────────────────────────────

Route::prefix('api')->middleware('auth')->group(function () {

    // Admin only
    Route::middleware('role:admin_elearning')->group(function () {
        Route::get('/search', [\App\Http\Controllers\Api\SearchController::class, 'search']);
    });

    // Guru & Siswa
    Route::middleware('role:guru,siswa')->group(function () {
        Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
        Route::post('/notifications/clear', [\App\Http\Controllers\Api\NotificationController::class, 'clearAll']);
    });
});

// ─────────────────────────────────────────────
// Admin E-Learning
// ─────────────────────────────────────────────

Route::middleware(['auth', 'role:admin_elearning'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

        // ── Users ──────────────────────────────────
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::get('/users/import/template', [\App\Http\Controllers\Admin\UserImportController::class, 'template'])->name('users.import.template');
        Route::post('/users/import', [\App\Http\Controllers\Admin\UserImportController::class, 'import'])->name('users.import');

        // ── Core Resources ─────────────────────────
        Route::resource('classes', \App\Http\Controllers\Admin\ClassController::class);
        Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);

        // ── Materials ──────────────────────────────
        Route::resource('materials', \App\Http\Controllers\Admin\MaterialController::class);
        Route::get('/materials/statistics', [\App\Http\Controllers\Admin\MaterialController::class, 'statistics'])->name('materials.statistics');
        Route::get('/materials/{material}/download', [\App\Http\Controllers\Admin\MaterialController::class, 'download'])->name('materials.download');
        Route::get('/materials/{material}/preview', [\App\Http\Controllers\Admin\MaterialController::class, 'preview'])->name('materials.preview');

        // ── Assignments ────────────────────────────
        Route::resource('assignments', \App\Http\Controllers\Admin\AssignmentController::class);
        Route::get('/assignments/statistics', [\App\Http\Controllers\Admin\AssignmentController::class, 'statistics'])->name('assignments.statistics');
        Route::get('/assignments/{assignment}/submissions', [\App\Http\Controllers\Admin\AssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::scopeBindings()->group(function () {
            Route::post('/assignments/{assignment}/submissions/{submission}/grade', [\App\Http\Controllers\Admin\AssignmentController::class, 'gradeSubmission'])->name('assignments.gradeSubmission');
        });

        // ── Grades ─────────────────────────────────
        Route::resource('grades', \App\Http\Controllers\Admin\GradeController::class)->only(['index', 'edit', 'update']);
        Route::get('/grades/by-class/{class}', [\App\Http\Controllers\Admin\GradeController::class, 'byClass'])->name('grades.byClass');
        Route::get('/grades/by-student/{student}', [\App\Http\Controllers\Admin\GradeController::class, 'byStudent'])->name('grades.byStudent');
        Route::get('/grades/report', [\App\Http\Controllers\Admin\GradeController::class, 'report'])->name('grades.report');
        Route::get('/grades/export', [\App\Http\Controllers\Admin\GradeController::class, 'exportCSV'])->name('grades.export');

        // ── Attendance ─────────────────────────────
        Route::resource('attendance', \App\Http\Controllers\Admin\AttendanceController::class)
            ->only(['index', 'create', 'store', 'show', 'destroy'])
            ->parameter('attendance', 'session');
        Route::get('/attendance/by-class/{class}', [\App\Http\Controllers\Admin\AttendanceController::class, 'byClass'])->name('attendance.byClass');
        Route::get('/attendance/by-student/{student}', [\App\Http\Controllers\Admin\AttendanceController::class, 'byStudent'])->name('attendance.byStudent');
        Route::get('/attendance/export', [\App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('attendance.export');

        // ── Settings ───────────────────────────────
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/reset', [\App\Http\Controllers\Admin\SettingController::class, 'reset'])->name('settings.reset');
        Route::delete('/banners/{id}', [\App\Http\Controllers\Admin\SettingController::class, 'deleteBanner'])->name('banners.delete');
        Route::patch('/banners/{id}/toggle', [\App\Http\Controllers\Admin\SettingController::class, 'toggleBanner'])->name('banners.toggle');
        Route::post('/banners/reorder', [\App\Http\Controllers\Admin\SettingController::class, 'reorderBanners'])->name('banners.reorder');

        // ── Class Subjects ─────────────────────────
        Route::get('/classes/{class}/subjects', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'getByClass'])->name('classes.subjects');
        Route::get('/classes/{class}/subjects/create', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'create'])->name('class-subjects.create');
        Route::post('/classes/{class}/subjects', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'store'])->name('class-subjects.store');
        Route::get('/classes/{class}/subjects/{classSubject}/edit', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'edit'])->name('class-subjects.edit');
        Route::put('/classes/{class}/subjects/{classSubject}', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'update'])->name('class-subjects.update');
        Route::delete('/classes/{class}/subjects/{classSubject}', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'destroy'])->name('class-subjects.destroy');
        Route::get('/class-subjects/{classSubject}/students', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'getStudents'])->name('class-subjects.students');

        // ── Schedules ──────────────────────────────
        Route::get('/classes/{class}/schedules/create', [\App\Http\Controllers\Admin\ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/classes/{class}/schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/classes/{class}/schedules/{schedule}/edit', [\App\Http\Controllers\Admin\ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/classes/{class}/schedules/{schedule}', [\App\Http\Controllers\Admin\ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/classes/{class}/schedules/{schedule}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');

        // Bulk Schedules
        Route::get('/classes/{class}/schedules/bulk', [\App\Http\Controllers\Admin\BulkScheduleController::class, 'edit'])->name('schedules.bulk.edit');
        Route::post('/classes/{class}/schedules/bulk', [\App\Http\Controllers\Admin\BulkScheduleController::class, 'store'])->name('schedules.bulk.store');

        // ── Class Students ─────────────────────────
        Route::get('/classes/{class}/students', [\App\Http\Controllers\Admin\ClassStudentController::class, 'index'])->name('classes.students');
        Route::post('/classes/{class}/students', [\App\Http\Controllers\Admin\ClassStudentController::class, 'store'])->name('classes.students.store');
        Route::delete('/classes/{class}/students/{student}', [\App\Http\Controllers\Admin\ClassStudentController::class, 'destroy'])->name('classes.students.destroy');

        // ── Rekap Nilai ────────────────────────────
        Route::get('/rekap-nilai', [\App\Http\Controllers\Admin\RekapNilaiController::class, 'index'])->name('rekap-nilai.index');
        Route::get('/rekap-nilai/export', [\App\Http\Controllers\Admin\RekapNilaiController::class, 'export'])->name('rekap-nilai.export');

        // ── Storage ────────────────────────────────
        Route::get('/storage', [\App\Http\Controllers\Admin\StorageController::class, 'index'])->name('storage.index');
        Route::post('/storage/delete', [\App\Http\Controllers\Admin\StorageController::class, 'delete'])->name('storage.delete');
        Route::post('/storage/cleanup', [\App\Http\Controllers\Admin\StorageController::class, 'cleanup'])->name('storage.cleanup');
    });

// ─────────────────────────────────────────────
// Guru
// ─────────────────────────────────────────────

Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            $classSubjects = \App\Models\ClassSubject::where('teacher_id', auth()->id())
                ->withCount(['materials', 'assignments'])
                ->with([
                    'eClass.students',
                    'subject',
                ])
                ->orderBy('e_class_id')
                ->get();

            // 1 guru = many class_subject (mapel yang diajar)
            $totalClassSubjects = $classSubjects->count();

            // Total kelas unik yang diajar (berdasarkan e_class_id)
            $totalClasses = $classSubjects->pluck('e_class_id')->unique()->count();

            // Total siswa unik tanpa double count antar mapel di kelas yang sama
            $uniqueClasses = $classSubjects->pluck('eClass')->filter()->unique('id');
            $totalStudents = $uniqueClasses->flatMap(fn ($c) => $c->students->pluck('id'))->unique()->count();

            // Materi & tugas harus spesifik per class_subject (bukan global per kelas)
            $totalMaterials   = (int) $classSubjects->sum('materials_count');
            $totalAssignments = (int) $classSubjects->sum('assignments_count');

            return view('guru.dashboard', compact(
                'classSubjects', 'totalClassSubjects', 'totalClasses',
                'totalStudents', 'totalMaterials', 'totalAssignments'
            ));
        })->name('dashboard');

        // Classes list
        Route::get('/classes', function () {
            $classSubjects = \App\Models\ClassSubject::where('teacher_id', auth()->id())
                ->withCount(['materials', 'assignments'])
                ->with(['eClass' => fn ($q) => $q->with('students'), 'subject'])
                ->orderBy('e_class_id')
                ->get();

            return view('guru.classes.index', compact('classSubjects'));
        })->name('classes.index');

        // Classes show (detail)
        Route::get('/classes/{classSubject}', [\App\Http\Controllers\Guru\ClassController::class, 'show'])->name('classes.show');

        // ── Class-Subject scoped Materials ─────────
        Route::get('/class-subjects/{classSubject}/materials', [\App\Http\Controllers\Guru\MaterialController::class, 'indexByClassSubject'])->name('class-subjects.materials.index');
        Route::get('/class-subjects/{classSubject}/materials/create', [\App\Http\Controllers\Guru\MaterialController::class, 'createByClassSubject'])->name('class-subjects.materials.create');
        Route::post('/class-subjects/{classSubject}/materials', [\App\Http\Controllers\Guru\MaterialController::class, 'storeByClassSubject'])->name('class-subjects.materials.store');

        // ── Class-Subject scoped Assignments ───────
        Route::get('/class-subjects/{classSubject}/assignments', [\App\Http\Controllers\Guru\AssignmentController::class, 'indexByClassSubject'])->name('class-subjects.assignments.index');
        Route::get('/class-subjects/{classSubject}/assignments/create', [\App\Http\Controllers\Guru\AssignmentController::class, 'createByClassSubject'])->name('class-subjects.assignments.create');
        Route::post('/class-subjects/{classSubject}/assignments', [\App\Http\Controllers\Guru\AssignmentController::class, 'storeByClassSubject'])->name('class-subjects.assignments.store');

        // ── Backward-compat Resource Routes ────────
        Route::resource('materials', \App\Http\Controllers\Guru\MaterialController::class);
        Route::resource('assignments', \App\Http\Controllers\Guru\AssignmentController::class);

        // ── Grades ─────────────────────────────────
        Route::resource('grades', \App\Http\Controllers\Guru\GradeController::class)
            ->only(['index', 'edit', 'update'])
            ->parameters(['grades' => 'submission']);

        // ── Attendance ─────────────────────────────
        Route::resource('attendance', \App\Http\Controllers\Guru\AttendanceController::class);
        Route::post('attendance/{attendance}/close', [\App\Http\Controllers\Guru\AttendanceController::class, 'close'])->name('attendance.close');
        Route::post('attendance/{attendance}/cancel', [\App\Http\Controllers\Guru\AttendanceController::class, 'cancel'])->name('attendance.cancel');

        // Backward-compat: old views posting to {session}
        Route::model('session', AttendanceSession::class);
        Route::post('attendance/{session}/close', [\App\Http\Controllers\Guru\AttendanceController::class, 'close']);
        Route::post('attendance/{session}/cancel', [\App\Http\Controllers\Guru\AttendanceController::class, 'cancel']);

        // ── Submission Download ─────────────────────
        Route::get('/submissions/{submission}/download', function (\App\Models\Submission $submission) {
            $assignment = $submission->assignment;
            abort_unless($assignment, 404);

            $class = $assignment->eClass;
            abort_unless($class, 404);
            abort_if(! $class->isTeachedBy(auth()->id()), 403);

            $relative = ltrim($submission->file_path ?? '', '/');
            abort_if($relative === '', 404);

            $normalized = preg_replace('#^storage/#', '', $relative);

            $fullPath = collect([
                storage_path('app/public/' . $normalized),
                storage_path('app/' . $relative),
                storage_path('app/' . $normalized),
            ])->first(fn ($p) => file_exists($p));

            abort_unless($fullPath, 404);

            return response()->download($fullPath);
        })->name('submissions.download');

        // ── Rekap Nilai ────────────────────────────
        Route::get('/rekap-nilai', [\App\Http\Controllers\Guru\RekapNilaiController::class, 'index'])->name('rekap-nilai.index');
        Route::get('/rekap-nilai/export', [\App\Http\Controllers\Guru\RekapNilaiController::class, 'export'])->name('rekap-nilai.export');
    });

// ─────────────────────────────────────────────
// Siswa
// ─────────────────────────────────────────────

Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            $myClasses        = auth()->user()->classes;
            $totalAssignments = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))->count();
            $totalSubmissions = \App\Models\Submission::where('student_id', auth()->id())->count();
            $averageGrade     = auth()->user()->grades()->avg('score');

            return view('siswa.dashboard', compact('myClasses', 'totalAssignments', 'totalSubmissions', 'averageGrade'));
        })->name('dashboard');

        // ── Subjects ───────────────────────────────
        Route::get('/subjects', function () {
            $classes = auth()->user()->classes()
                ->with(['classSubjects.subject', 'classSubjects.teacher', 'schedules', 'materials', 'assignments'])
                ->get();

            return view('siswa.subjects.index', compact('classes'));
        })->name('subjects.index');

        Route::get('/subjects/{class}', function (\App\Models\EClass $class) {
            abort_if(! auth()->user()->classes->contains($class), 403);
            $class->load(['classSubjects.subject', 'classSubjects.teacher', 'schedules', 'materials', 'assignments', 'students']);

            return view('siswa.subjects.show', compact('class'));
        })->name('subjects.show');

        // ── Schedule ───────────────────────────────
        Route::get('/schedule', function () {
            $classes = auth()->user()
                ->classes()
                ->with(['classSubjects' => fn ($q) => $q->with(['subject', 'teacher']), 'schedules'])
                ->get();

            return view('siswa.schedule.index', compact('classes'));
        })->name('schedule.index');

        // ── Assignments ────────────────────────────
        Route::get('/assignments', function () {
            $myClassIds = auth()->user()->classes()->pluck('e_classes.id');

            $assignments = \App\Models\Assignment::query()
                ->whereIn('e_class_id', $myClassIds)
                ->with(['eClass', 'classSubject.subject', 'classSubject.teacher'])
                ->orderBy('deadline', 'desc')
                ->get();

            return view('siswa.assignments.index', compact('assignments'));
        })->name('assignments.index');

        Route::get('/assignments/{assignment}', function (\App\Models\Assignment $assignment) {
            abort_if(! auth()->user()->classes->contains($assignment->eClass), 403);

            return view('siswa.assignments.show', compact('assignment'));
        })->name('assignments.show');

        // Fallback: old forms POSTing to show URL
        Route::post('/assignments/{assignment}', function (\Illuminate\Http\Request $request, \App\Models\Assignment $assignment) {
            return redirect()->route('siswa.submissions.store', $assignment);
        });

        // Assignment Download
        Route::get('/assignments/{assignment}/download', function (\App\Models\Assignment $assignment) {
            abort_if(! auth()->user()->classes->contains($assignment->eClass), 403);

            $relative   = ltrim($assignment->file_path ?? '', '/');
            abort_if($relative === '', 404);

            $normalized = preg_replace('#^storage/#', '', $relative);

            $fullPath = collect([
                storage_path('app/public/' . $normalized),
                storage_path('app/' . $relative),
                storage_path('app/' . $normalized),
            ])->first(fn ($p) => file_exists($p));

            abort_unless($fullPath, 404);

            return response()->download($fullPath);
        })->name('assignments.download');

        // Assignment Submit URL (GET redirect guard)
        Route::get('/assignments/{assignment}/submit', function (\App\Models\Assignment $assignment) {
            return redirect()->route('siswa.assignments.show', $assignment);
        });

        // ── Submissions ────────────────────────────
        Route::post('/assignments/{assignment}/submit', function (\App\Models\Assignment $assignment, \Illuminate\Http\Request $request) {
            abort_if(! auth()->user()->classes->contains($assignment->eClass), 403);

            $request->validate([
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:' . config('upload.submission_max_kb'),
            ]);

            $submission = \App\Models\Submission::firstOrNew([
                'assignment_id' => $assignment->id,
                'student_id'    => auth()->id(),
            ]);

            if ($submission->exists && $submission->file_path) {
                \App\Services\FileUploadService::deleteFile($submission->file_path);
            }

            $stored = $request->file('file')->store('submissions', 'public');

            $submission->file_path   = 'storage/' . $stored;
            $submission->submitted_at = now();
            $submission->save();

            return redirect()->route('siswa.assignments.show', $assignment)->with('success', 'Pengumpulan berhasil dikirim.');
        })->name('submissions.store');

        Route::get('/submissions/{submission}/download', function (\App\Models\Submission $submission) {
            abort_if($submission->student_id !== auth()->id(), 403);

            $relative   = ltrim($submission->file_path ?? '', '/');
            abort_if($relative === '', 404);

            $normalized = preg_replace('#^storage/#', '', $relative);

            $fullPath = collect([
                storage_path('app/public/' . $normalized),
                storage_path('app/' . $relative),
                storage_path('app/' . $normalized),
            ])->first(fn ($p) => file_exists($p));

            abort_unless($fullPath, 404);

            return response()->download($fullPath);
        })->name('submissions.download');

        // ── Quizzes ────────────────────────────────
        Route::get('/quizzes', fn () => view('siswa.quizzes.index'))->name('quizzes.index');

        // ── Attendance ─────────────────────────────
        Route::get('/attendance/{classSubject}', [\App\Http\Controllers\Siswa\AttendanceController::class, 'show'])->name('attendance.show');
        Route::post('/attendance/{session}/submit', [\App\Http\Controllers\Siswa\AttendanceController::class, 'store'])->name('attendance.store');

        // ── Materials Download ──────────────────────
        Route::get('/materials/{material}/download', function (\App\Models\Material $material) {
            abort_if(! auth()->user()->classes->contains($material->eClass), 403);

            $relative   = ltrim($material->file_path ?? '', '/');
            abort_if($relative === '', 404);

            $normalized = preg_replace('#^storage/#', '', $relative);

            $fullPath = collect([
                storage_path('app/public/' . $normalized),
                storage_path('app/' . $relative),
                storage_path('app/' . $normalized),
            ])->first(fn ($p) => file_exists($p));

            abort_unless($fullPath, 404);

            return response()->download($fullPath);
        })->name('materials.download');

        // ── Legacy / Backward-compat ───────────────
        Route::get('/classes', fn () => view('siswa.subjects.index', ['classes' => auth()->user()->classes]))->name('classes.index');
        Route::get('/grades', fn () => view('siswa.grades.index', ['grades' => auth()->user()->grades]))->name('grades.index');
    });