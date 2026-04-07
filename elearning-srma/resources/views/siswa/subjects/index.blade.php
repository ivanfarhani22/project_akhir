@extends('layouts.siswa')

@section('title', 'Mata Pelajaran')
@section('icon', 'fas fa-book')

@section('content')
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-book"></i>
            Mata Pelajaran
        </h1>
        <p class="page-description">Daftar mata pelajaran yang Anda pelajari</p>
    </div>

    @if($classes->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            @foreach($classes as $class)
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 style="font-size: 18px; font-weight: 600; color: var(--secondary); margin-bottom: 5px;">
                                {{ $class->subject->name }}
                            </h3>
                            <p style="color: #999; font-size: 14px;">{{ $class->name }}</p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Guru -->
                        <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                            <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;">Pengajar</p>
                            <p style="color: var(--secondary); font-weight: 600;">{{ $class->teacher->name }}</p>
                        </div>

                        <!-- Deskripsi -->
                        @if($class->description)
                            <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                                <p style="color: #999; font-size: 12px; margin-bottom: 4px; text-transform: uppercase;">Deskripsi</p>
                                <p style="color: #666; font-size: 13px;">{{ Str::limit($class->description, 100) }}</p>
                            </div>
                        @endif

                        <!-- Jadwal -->
                        <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                            <p style="color: #999; font-size: 12px; margin-bottom: 8px; text-transform: uppercase;">Jadwal</p>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-calendar" style="color: var(--primary);"></i>
                                <span style="color: #666; font-size: 13px;">
                                    @if($class->schedules && $class->schedules->count() > 0)
                                        @php $schedule = $class->schedules->first(); @endphp
                                        {{ ucfirst($schedule->day_of_week) }}
                                        @if($schedule->start_time)
                                            • {{ \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') }}
                                            @if($schedule->end_time)
                                                - {{ \Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') }}
                                            @endif
                                        @endif
                                    @else
                                        TBA
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Statistik -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                            @php
                                $materials = $class->materials->count();
                                $assignments = $class->assignments->count();
                            @endphp
                            <div style="text-align: center;">
                                <p style="color: #999; font-size: 12px; margin-bottom: 4px;">Materi</p>
                                <p style="font-size: 20px; font-weight: 700; color: var(--primary);">{{ $materials }}</p>
                            </div>
                            <div style="text-align: center;">
                                <p style="color: #999; font-size: 12px; margin-bottom: 4px;">Tugas</p>
                                <p style="font-size: 20px; font-weight: 700; color: #f39c12;">{{ $assignments }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <a href="{{ route('siswa.subjects.show', $class->id) }}" class="btn btn-primary btn-sm" style="justify-content: center;">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('siswa.assignments.index') }}?class={{ $class->id }}" class="btn btn-secondary btn-sm" style="justify-content: center;">
                                <i class="fas fa-tasks"></i> Tugas
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 64px; color: #ddd; margin-bottom: 20px; display: block;"></i>
                <p style="color: #999; font-size: 16px; margin-bottom: 20px;">Anda belum terdaftar di mata pelajaran apapun</p>
                <p style="color: #666; font-size: 14px;">Hubungi administrator untuk mendaftar ke mata pelajaran</p>
            </div>
        </div>
    @endif
@endsection
