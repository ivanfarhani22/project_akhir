@extends('layouts.siswa')

@section('title', $class->subject->name)
@section('icon', 'fas fa-book')

@section('content')
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h1 class="page-title">
                <i class="fas fa-book"></i>
                {{ $class->subject->name }}
            </h1>
            <a href="{{ route('siswa.subjects.index') }}" style="color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <p class="page-description">Kelas: {{ $class->name }} • Pengajar: {{ $class->teacher->name }}</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-info-circle" style="color: #2980b9; margin-right: 10px;"></i>
                    Informasi Kelas
                </div>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;">Pengajar</p>
                    <p style="color: var(--secondary); font-weight: 600;">{{ $class->teacher->name }}</p>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;">Jadwal</p>
                    @if($class->schedules && $class->schedules->count() > 0)
                        @php $schedule = $class->schedules->first(); @endphp
                        <p style="color: var(--secondary); font-weight: 600;">
                            {{ ucfirst($schedule->day_of_week) }}
                            @if($schedule->start_time)
                                • {{ \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') }}
                                @if($schedule->end_time)
                                    - {{ \Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') }}
                                @endif
                            @endif
                        </p>
                    @else
                        <p style="color: var(--secondary); font-weight: 600;">TBA</p>
                    @endif
                </div>

                @if($class->description)
                    <div>
                        <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;">Deskripsi</p>
                        <p style="color: #666; font-size: 13px;">{{ $class->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-chart-bar" style="color: #27ae60; margin-right: 10px;"></i>
                    Statistik
                </div>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                    <p style="color: #999; font-size: 12px; margin-bottom: 4px;">MATERI PEMBELAJARAN</p>
                    <p style="font-size: 24px; font-weight: 700; color: var(--primary);">{{ $class->materials->count() }}</p>
                </div>
                
                <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                    <p style="color: #999; font-size: 12px; margin-bottom: 4px;">TUGAS</p>
                    <p style="font-size: 24px; font-weight: 700; color: #f39c12;">{{ $class->assignments->count() }}</p>
                </div>

                <div>
                    <p style="color: #999; font-size: 12px; margin-bottom: 4px;">SISWA TERDAFTAR</p>
                    <p style="font-size: 24px; font-weight: 700; color: #2980b9;">{{ $class->students->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MATERIALS SECTION -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-file-alt" style="color: var(--primary); margin-right: 10px;"></i>
                Materi Pembelajaran
            </div>
        </div>
        <div class="card-body">
            @if($class->materials->count() > 0)
                <div style="max-height: 600px; overflow-y: auto;">
                    @foreach($class->materials as $material)
                        <div style="padding: 15px; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: start;">
                            <div>
                                <h4 style="font-weight: 600; color: var(--secondary); margin-bottom: 5px;">{{ $material->title }}</h4>
                                @if($material->description)
                                    <p style="color: #666; font-size: 13px; margin-bottom: 8px;">{{ Str::limit($material->description, 150) }}</p>
                                @endif
                                <p style="color: #999; font-size: 12px;">
                                    <i class="fas fa-clock"></i>
                                    {{ $material->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm" style="white-space: nowrap; margin-left: 10px;">
                                <i class="fas fa-download"></i> Buka
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="fas fa-file-alt" style="font-size: 48px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                    <p style="color: #999;">Belum ada materi pembelajaran</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ASSIGNMENTS SECTION -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-tasks" style="color: #f39c12; margin-right: 10px;"></i>
                Tugas dan Penilaian
            </div>
        </div>
        <div class="card-body">
            @if($class->assignments->count() > 0)
                <div style="max-height: 600px; overflow-y: auto;">
                    @foreach($class->assignments as $assignment)
                        @php
                            $submission = \App\Models\Submission::where('student_id', auth()->id())
                                ->where('assignment_id', $assignment->id)
                                ->first();
                            $isLate = $submission && $submission->submitted_at && $submission->submitted_at > $assignment->deadline;
                        @endphp
                        <div style="padding: 15px; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                <div>
                                    <h4 style="font-weight: 600; color: var(--secondary); margin-bottom: 4px;">{{ $assignment->title }}</h4>
                                    <p style="color: #666; font-size: 13px;">{{ Str::limit($assignment->description, 100) }}</p>
                                </div>
                                <span style="background: {{ $submission && $submission->submitted_at ? '#28a745' : '#dc3545' }}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; margin-left: 10px;">
                                    {{ $submission && $submission->submitted_at ? '✓ Terkumpul' : '✗ Belum' }}
                                </span>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin-bottom: 10px; padding-top: 10px; border-top: 1px solid #f0f0f0;">
                                <div>
                                    <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 3px;">Deadline</p>
                                    <p style="color: var(--secondary); font-weight: 600; font-size: 13px;">{{ $assignment->deadline->format('d M H:i') }}</p>
                                </div>
                                @if($submission && $submission->grade)
                                    <div>
                                        <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 3px;">Nilai</p>
                                        <p style="color: var(--primary); font-weight: 600; font-size: 13px;">{{ $submission->grade->score }}</p>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;">
                                <i class="fas fa-arrow-right"></i> Lihat Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="fas fa-tasks" style="font-size: 48px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                    <p style="color: #999;">Belum ada tugas untuk mata pelajaran ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ATTENDANCE SECTION -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-clipboard-list" style="color: #8b5cf6; margin-right: 10px;"></i>
                Presensi
            </div>
        </div>
        <div class="card-body">
            @php
                // Get all class subjects for this class, check if any has open attendance
                $openSessions = [];
                foreach($class->classSubjects as $cs) {
                    $session = $cs->attendanceSessions()
                        ->where('status', 'open')
                        ->where('attendance_date', today())
                        ->first();
                    if ($session) {
                        $openSessions[$cs->id] = $session;
                    }
                }
            @endphp

            @if(count($openSessions) > 0)
                <div style="background: #f0fdf4; border-left: 4px solid #22c55e; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="color: #166534; margin: 0 0 12px 0; font-weight: 600;">
                        <i class="fas fa-circle-notch" style="animation: spin 1s linear infinite;"></i> Presensi Terbuka Hari Ini
                    </p>
                    @foreach($openSessions as $classSubjectId => $session)
                        <a href="{{ route('siswa.attendance.show', $class->classSubjects->find($classSubjectId)) }}" class="btn btn-success btn-sm" style="width: 100%; justify-content: center; margin-bottom: 8px;">
                            <i class="fas fa-check-circle"></i> Lakukan Absensi - {{ $class->classSubjects->find($classSubjectId)->subject->name }}
                        </a>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 24px 20px; color: #94a3b8;">
                    <i class="fas fa-inbox" style="font-size: 32px; display: block; margin-bottom: 8px;"></i>
                    <p style="margin: 0;">Belum ada presensi hari ini</p>
                    <p style="font-size: 12px; margin-top: 4px;">Guru akan membuka presensi saat pelajaran dimulai</p>
                </div>
            @endif

            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border);">
                @foreach($class->classSubjects as $cs)
                    <a href="{{ route('siswa.attendance.show', $cs) }}" style="color: var(--primary); text-decoration: none; font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">
                        <i class="fas fa-history"></i> Riwayat Presensi - {{ $cs->subject->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
@endsection
