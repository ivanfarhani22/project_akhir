@extends('layouts.siswa')

@section('title', 'Beranda')
@section('icon', 'fas fa-home')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Selamat datang,</p>
        <h1 class="page-title">
            <i class="fas fa-home"></i>
            Beranda
        </h1>
        <p class="page-description">Lihat ringkasan tugas, jadwal, dan pengumuman Anda</p>
    </div>

    <!-- QUICK STATS -->
    @php
        $myClasses = auth()->user()->classes;
        $upcomingAssignments = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->count();
        $totalAssignments = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))->count();
        $submittedAssignments = \App\Models\Submission::where('student_id', auth()->id())
            ->whereNotNull('submitted_at')
            ->count();
        $averageGrade = auth()->user()->grades()->avg('score');
    @endphp

    <div class="stats-grid">
        <div class="stat-card classes">
            <div class="stat-info">
                <h3>Mata Pelajaran</h3>
                <div class="stat-value">{{ $myClasses->count() }}</div>
            </div>
            <i class="fas fa-book stat-icon"></i>
        </div>

        <div class="stat-card assignments">
            <div class="stat-info">
                <h3>Tugas Mendatang</h3>
                <div class="stat-value">{{ $upcomingAssignments }}</div>
            </div>
            <i class="fas fa-clock stat-icon"></i>
        </div>

        <div class="stat-card submissions">
            <div class="stat-info">
                <h3>Tugas Terkumpul</h3>
                <div class="stat-value">{{ $submittedAssignments }}/{{ $totalAssignments }}</div>
            </div>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>

        <div class="stat-card grades">
            <div class="stat-info">
                <h3>Nilai Rata-rata</h3>
                <div class="stat-value">{{ $averageGrade ? number_format($averageGrade, 1) : '-' }}</div>
            </div>
            <i class="fas fa-star stat-icon"></i>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
        <!-- UPCOMING ASSIGNMENTS -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-hourglass-end" style="color: #f39c12; margin-right: 10px;"></i>
                    Tugas Mendatang
                </div>
            </div>
            <div class="card-body">
                @php
                    $upcomingTasks = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))
                        ->where('deadline', '>=', now())
                        ->orderBy('deadline')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($upcomingTasks->count() > 0)
                    <div style="max-height: 400px; overflow-y: auto;">
                        @foreach($upcomingTasks as $task)
                            <div style="padding: 12px 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <p style="font-weight: 600; color: var(--secondary); margin-bottom: 4px; font-size: 14px;">
                                        {{ $task->title }}
                                    </p>
                                    <p style="color: #999; font-size: 12px; margin-bottom: 4px;">
                                        {{ $task->eClass->subject->name }}
                                    </p>
                                    <p style="color: #f39c12; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $task->deadline->format('d M, H:i') }}
                                    </p>
                                </div>
                                @php
                                    $submission = \App\Models\Submission::where('student_id', auth()->id())
                                        ->where('assignment_id', $task->id)
                                        ->first();
                                @endphp
                                <span style="background: {{ $submission && $submission->submitted_at ? '#28a745' : '#dc3545' }}; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; white-space: nowrap; margin-left: 10px;">
                                    {{ $submission && $submission->submitted_at ? 'Terkumpul' : 'Belum' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="{{ route('siswa.assignments.index') }}" style="color: var(--primary); font-weight: 600; font-size: 14px; text-decoration: none;">
                            Lihat Semua Tugas <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div style="text-align: center; padding: 30px 0;">
                        <i class="fas fa-check-circle" style="font-size: 48px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                        <p style="color: #999; font-size: 14px;">Tidak ada tugas mendatang</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- TODAY'S SCHEDULE -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-calendar-day" style="color: #2980b9; margin-right: 10px;"></i>
                    Jadwal Hari Ini
                </div>
            </div>
            <div class="card-body">
                @php
                    $today = \Carbon\Carbon::now();
                    $dayOfWeek = strtolower($today->format('l'));
                    $todaySchedules = $myClasses->where('day_of_week', $dayOfWeek)->sortBy('start_time');
                @endphp
                
                @if($todaySchedules->count() > 0)
                    <div style="max-height: 400px; overflow-y: auto;">
                        @foreach($todaySchedules as $schedule)
                            <div style="padding: 12px 0; border-bottom: 1px solid var(--border);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <p style="font-weight: 600; color: var(--secondary); font-size: 14px;">
                                        {{ $schedule->subject->name }}
                                    </p>
                                    <span style="background: #2980b9; color: white; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                        {{ $schedule->start_time ?? 'TBA' }}
                                    </span>
                                </div>
                                <p style="color: #999; font-size: 12px;">
                                    Pengajar: {{ $schedule->teacher->name }}
                                </p>
                                <p style="color: #666; font-size: 12px;">
                                    Kelas: {{ $schedule->name }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 30px 0;">
                        <i class="fas fa-calendar" style="font-size: 48px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                        <p style="color: #999; font-size: 14px;">Tidak ada jadwal hari ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ANNOUNCEMENTS / QUICK ACTIONS -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-bell" style="color: #27ae60; margin-right: 10px;"></i>
                    Akses Cepat
                </div>
            </div>
            <div class="card-body">
                <div style="display: grid; gap: 10px;">
                    <a href="{{ route('siswa.subjects.index') }}" style="padding: 15px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 15px; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
                        <i class="fas fa-book" style="font-size: 24px; color: #27ae60;"></i>
                        <div>
                            <p style="font-weight: 600; color: var(--secondary); margin-bottom: 2px;">Mata Pelajaran</p>
                            <p style="font-size: 12px; color: #999;">Lihat semua kelas Anda</p>
                        </div>
                    </a>

                    <a href="{{ route('siswa.schedule.index') }}" style="padding: 15px; background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 15px; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
                        <i class="fas fa-calendar-alt" style="font-size: 24px; color: #2980b9;"></i>
                        <div>
                            <p style="font-weight: 600; color: var(--secondary); margin-bottom: 2px;">Jadwal Lengkap</p>
                            <p style="font-size: 12px; color: #999;">Lihat jadwal mingguan</p>
                        </div>
                    </a>

                    <a href="{{ route('siswa.assignments.index') }}" style="padding: 15px; background: linear-gradient(135deg, #fff3e0, #ffe0b2); border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 15px; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
                        <i class="fas fa-tasks" style="font-size: 24px; color: #f39c12;"></i>
                        <div>
                            <p style="font-weight: 600; color: var(--secondary); margin-bottom: 2px;">Semua Tugas</p>
                            <p style="font-size: 12px; color: #999;">Kelola tugas Anda</p>
                        </div>
                    </a>

                    <a href="{{ route('siswa.quizzes.index') }}" style="padding: 15px; background: linear-gradient(135deg, #f3e5f5, #e1bee7); border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 15px; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
                        <i class="fas fa-question-circle" style="font-size: 24px; color: #9b59b6;"></i>
                        <div>
                            <p style="font-weight: 600; color: var(--secondary); margin-bottom: 2px;">Quiz / Ujian</p>
                            <p style="font-size: 12px; color: #999;">Ikuti quiz dan ujian</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- WELCOME MESSAGE -->
    <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, #743D52 100%); color: white; margin-top: 20px;">
        <div class="card-body">
            <h3 style="color: white; margin-bottom: 10px;">
                <i class="fas fa-lightbulb"></i>
                Tips Belajar
            </h3>
            <p style="margin-bottom: 10px; opacity: 0.9;">
                Jangan lupa untuk selalu mengecek jadwal pelajaran dan mengumpulkan tugas sebelum deadline. Semangat belajar! 💪
            </p>
        </div>
    </div>
@endsection
