@extends('layouts.guru')

@section('title', 'Dashboard Guru')
@section('icon', 'fas fa-graduation-cap')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Selamat datang,</p>
        <h1 class="page-title">
            <i class="fas fa-graduation-cap"></i>
            Dashboard Guru
        </h1>
        <p class="page-description">Kelola kelas, materi, dan penilaian Anda</p>
    </div>

    <!-- STATISTICS -->
    <div class="stats-grid">
        <div class="stat-card classes">
            <div class="stat-info">
                <h3>Kelas Ajar</h3>
                <div class="stat-number">{{ $totalClasses }}</div>
            </div>
            <i class="fas fa-chalkboard stat-icon"></i>
        </div>

        <div class="stat-card subjects">
            <div class="stat-info">
                <h3>Siswa</h3>
                <div class="stat-number">{{ $totalStudents }}</div>
            </div>
            <i class="fas fa-users stat-icon"></i>
        </div>

        <div class="stat-card activities">
            <div class="stat-info">
                <h3>Materi Diunggah</h3>
                <div class="stat-number">{{ $totalMaterials }}</div>
            </div>
            <i class="fas fa-file-alt stat-icon"></i>
        </div>

        <div class="stat-card users">
            <div class="stat-info">
                <h3>Tugas Diberikan</h3>
                <div class="stat-number">{{ $totalAssignments }}</div>
            </div>
            <i class="fas fa-tasks stat-icon"></i>
        </div>
    </div>

    <!-- MY CLASSES -->
    <div class="page-header" style="margin-top: 40px; margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 600; color: var(--secondary); margin-bottom: 15px;">
            <i class="fas fa-chalkboard" style="color: var(--primary); margin-right: 10px;"></i>
            Kelas Saya
        </h2>
    </div>

    @if($classes->isEmpty())
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                <p style="color: #999; font-size: 16px;">Belum ada kelas yang ditugaskan</p>
            </div>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($classes as $class)
                <div class="card">
                    <div class="card-body" style="padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 600; color: var(--secondary); margin-bottom: 5px;">
                                    {{ $class->name }}
                                </h3>
                                <p style="font-size: 13px; color: #999;">{{ $class->subject->name }}</p>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                            <div style="text-align: center;">
                                <div style="font-size: 11px; text-transform: uppercase; color: #999; font-weight: 600;">Siswa</div>
                                <div style="font-size: 20px; font-weight: 700; color: #0066cc;">{{ $class->students->count() }}</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 11px; text-transform: uppercase; color: #999; font-weight: 600;">Materi</div>
                                <div style="font-size: 20px; font-weight: 700; color: #28a745;">{{ $class->materials->count() }}</div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('guru.materials.index', ['class_id' => $class->id]) }}" class="btn btn-sm" style="flex: 1; background: #28a745; color: white; text-decoration: none; text-align: center;">
                                <i class="fas fa-book"></i> Materi
                            </a>
                            <a href="{{ route('guru.assignments.index', ['class_id' => $class->id]) }}" class="btn btn-sm" style="flex: 1; background: #0066cc; color: white; text-decoration: none; text-align: center;">
                                <i class="fas fa-tasks"></i> Tugas
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- RECENT ACTIVITY -->
    <div class="page-header" style="margin-top: 40px; margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 600; color: var(--secondary); margin-bottom: 15px;">
            <i class="fas fa-history" style="color: var(--primary); margin-right: 10px;"></i>
            Aktivitas Terbaru
        </h2>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%;">Aksi</th>
                        <th style="width: 50%;">Deskripsi</th>
                        <th style="width: 25%;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\ActivityLog::where('user_id', auth()->id())->orderBy('timestamp', 'desc')->take(10)->get() as $log)
                        <tr>
                            <td>
                                <span class="badge badge-primary">{{ $log->action }}</span>
                            </td>
                            <td>{{ Str::limit($log->description, 60) }}</td>
                            <td style="font-size: 13px; color: #999;">
                                {{ \Carbon\Carbon::parse($log->timestamp)->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 40px 20px; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.3;"></i>
                                Belum ada aktivitas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
