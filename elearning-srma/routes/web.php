<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect home ke dashboard sesuai role
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin_elearning') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'guru') {
            return redirect()->route('guru.dashboard');
        } else {
            return redirect()->route('siswa.dashboard');
        }
    }
    return redirect()->route('login');
})->name('home');

// Dashboard Admin E-Learning
Route::middleware(['auth', 'role:admin_elearning'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('classes', \App\Http\Controllers\Admin\ClassController::class);
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    
    // Class Subject Management Routes
    Route::post('/classes/{class}/subjects', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'store'])->name('class-subjects.store');
    Route::get('/classes/{class}/subjects/create', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'create'])->name('class-subjects.create');
    Route::get('/classes/{class}/subjects/{classSubject}/edit', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'edit'])->name('class-subjects.edit');
    Route::put('/classes/{class}/subjects/{classSubject}', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'update'])->name('class-subjects.update');
    Route::delete('/classes/{class}/subjects/{classSubject}', [\App\Http\Controllers\Admin\ClassSubjectController::class, 'destroy'])->name('class-subjects.destroy');
    
    // Schedule Management Routes
    Route::get('/classes/{class}/schedules/create', [\App\Http\Controllers\Admin\ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/classes/{class}/schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/classes/{class}/schedules/{schedule}/edit', [\App\Http\Controllers\Admin\ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/classes/{class}/schedules/{schedule}', [\App\Http\Controllers\Admin\ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/classes/{class}/schedules/{schedule}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');
    
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    // Banner Management
    Route::delete('/banners/{id}', [\App\Http\Controllers\Admin\SettingController::class, 'deleteBanner'])->name('banners.delete');
    Route::patch('/banners/{id}/toggle', [\App\Http\Controllers\Admin\SettingController::class, 'toggleBanner'])->name('banners.toggle');
    Route::post('/banners/reorder', [\App\Http\Controllers\Admin\SettingController::class, 'reorderBanners'])->name('banners.reorder');
    
    // Student Management in Classes
    Route::get('/classes/{class}/students', [\App\Http\Controllers\Admin\ClassStudentController::class, 'index'])->name('classes.students');
    Route::post('/classes/{class}/students', [\App\Http\Controllers\Admin\ClassStudentController::class, 'store'])->name('classes.students.store');
    Route::delete('/classes/{class}/students/{student}', [\App\Http\Controllers\Admin\ClassStudentController::class, 'destroy'])->name('classes.students.destroy');
});

// Dashboard Guru
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', function () {
        // Guru hanya lihat class dimana dia mengajar (via classSubjects)
        $classes = \App\Models\EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))
            ->with(['classSubjects' => fn($q) => $q->where('teacher_id', auth()->id()), 'students', 'materials', 'assignments'])
            ->get();
        $totalClasses = $classes->count();
        $totalStudents = $classes->sum(fn($c) => $c->students->count());
        $totalMaterials = \App\Models\Material::whereIn('e_class_id', $classes->pluck('id'))->count();
        $totalAssignments = \App\Models\Assignment::whereIn('e_class_id', $classes->pluck('id'))->count();
        return view('guru.dashboard', compact('classes', 'totalClasses', 'totalStudents', 'totalMaterials', 'totalAssignments'));
    })->name('dashboard');
    
    Route::get('/classes', function () {
        // Guru hanya lihat class dimana dia mengajar
        $classes = \App\Models\EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', auth()->id()))
            ->with(['classSubjects' => fn($q) => $q->where('teacher_id', auth()->id()), 'students', 'materials', 'assignments'])
            ->get();
        return view('guru.classes.index', compact('classes'));
    })->name('classes.index');
    
    Route::resource('materials', \App\Http\Controllers\Guru\MaterialController::class);
    Route::resource('assignments', \App\Http\Controllers\Guru\AssignmentController::class);
    Route::resource('grades', \App\Http\Controllers\Guru\GradeController::class, ['only' => ['index', 'edit', 'update']]);
    
    // Attendance Management
    Route::resource('attendance', \App\Http\Controllers\Guru\AttendanceController::class);
    Route::post('attendance/{session}/close', [\App\Http\Controllers\Guru\AttendanceController::class, 'close'])->name('attendance.close');
    Route::post('attendance/{session}/cancel', [\App\Http\Controllers\Guru\AttendanceController::class, 'cancel'])->name('attendance.cancel');
});

// Dashboard Siswa
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', function () {
        $myClasses = auth()->user()->classes;
        $totalAssignments = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))->count();
        $totalSubmissions = \App\Models\Submission::where('student_id', auth()->id())->count();
        $averageGrade = auth()->user()->grades()->avg('score');
        return view('siswa.dashboard', compact('myClasses', 'totalAssignments', 'totalSubmissions', 'averageGrade'));
    })->name('dashboard');
    
    // Mata Pelajaran (Subjects)
    Route::get('/subjects', function () {
        $classes = auth()->user()->classes()->with(['classSubjects.subject', 'classSubjects.teacher', 'schedules', 'materials', 'assignments'])->get();
        return view('siswa.subjects.index', compact('classes'));
    })->name('subjects.index');
    
    Route::get('/subjects/{class}', function (\App\Models\EClass $class) {
        abort_if(!auth()->user()->classes->contains($class), 403);
        $class->load(['classSubjects.subject', 'classSubjects.teacher', 'schedules', 'materials', 'assignments', 'students']);
        return view('siswa.subjects.show', compact('class'));
    })->name('subjects.show');
    
    // Jadwal Pelajaran (Schedule)
    Route::get('/schedule', function () {
        // Eager load relasi untuk menghindari N+1 query
        $classes = auth()->user()
            ->classes()
            ->with(['classSubjects' => function($q) {
                $q->with(['subject', 'teacher']);
            }, 'schedules'])
            ->get();
        return view('siswa.schedule.index', compact('classes'));
    })->name('schedule.index');
    
    // Tugas (Assignments)
    Route::get('/assignments', function () {
        return view('siswa.assignments.index', ['assignments' => auth()->user()->submissions]);
    })->name('assignments.index');
    
    Route::get('/assignments/{assignment}', function (\App\Models\Assignment $assignment) {
        abort_if(!auth()->user()->classes->contains($assignment->eClass), 403);
        return view('siswa.assignments.show', compact('assignment'));
    })->name('assignments.show');
    
    // Quiz / Ujian
    Route::get('/quizzes', function () {
        return view('siswa.quizzes.index');
    })->name('quizzes.index');
    
    // Presensi/Absensi (Attendance)
    Route::get('/attendance/{classSubject}', [\App\Http\Controllers\Siswa\AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/{session}/submit', [\App\Http\Controllers\Siswa\AttendanceController::class, 'store'])->name('attendance.store');
    
    // Legacy routes for backward compatibility
    Route::get('/classes', function () {
        return view('siswa.subjects.index', ['classes' => auth()->user()->classes]);
    })->name('classes.index');
    
    Route::get('/grades', function () {
        return view('siswa.grades.index', ['grades' => auth()->user()->grades]);
    })->name('grades.index');
});

