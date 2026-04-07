@extends('layouts.siswa')

@section('title', 'Jadwal Pelajaran')
@section('icon', 'fas fa-calendar-alt')

@php
    $daysOrder = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    $dayLabels = ['monday'=>'Senin','tuesday'=>'Selasa','wednesday'=>'Rabu','thursday'=>'Kamis','friday'=>'Jumat','saturday'=>'Sabtu','sunday'=>'Minggu'];
    $dayColors = ['monday'=>'#6366f1','tuesday'=>'#0ea5e9','wednesday'=>'#10b981','thursday'=>'#f59e0b','friday'=>'#ef4444','saturday'=>'#8b5cf6','sunday'=>'#ec4899'];
    
    // Ensure $classes is defined (passed from route)
    if (!isset($classes)) {
        $classes = collect([]);
    }
    
    // Build schedule list from all classes' schedules
    $schedules = collect([]);
    foreach ($classes as $class) {
        if ($class->schedules && count($class->schedules) > 0) {
            foreach ($class->schedules as $schedule) {
                // Attach class info to schedule for display
                $schedule->class = $class;
                $schedules->push($schedule);
            }
        }
    }
@endphp

@section('content')
<style>
    .jadwal-header { margin-bottom: 28px; }
    .jadwal-header h1 { font-size: 24px; font-weight: 700; color: var(--secondary); margin-bottom: 4px; }
    .jadwal-header p  { color: #94a3b8; font-size: 14px; }

    /* Weekly table */
    .table-wrap { overflow-x: auto; border-radius: 12px; }
    .schedule-table { width: 100%; min-width: 640px; border-collapse: collapse; }
    .schedule-table thead th {
        background: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em; padding: 12px 16px; text-align: left;
    }
    .schedule-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .15s; }
    .schedule-table tbody tr:hover { background: #f8fafc; }
    .schedule-table tbody td { padding: 12px 16px; font-size: 14px; color: var(--secondary); vertical-align: middle; }
    .day-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; color: #fff;
    }
    .time-chip {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f1f5f9; color: #64748b; padding: 3px 10px; border-radius: 20px; font-size: 12px;
    }

    /* Subject cards */
    .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
    .subject-card {
        background: #fff; border: 1px solid #e8edf3; border-radius: 14px;
        overflow: hidden; transition: box-shadow .2s, transform .2s;
    }
    .subject-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.09); transform: translateY(-2px); }
    .subject-card-top {
        padding: 16px 18px 14px; display: flex; justify-content: space-between; align-items: flex-start;
        border-bottom: 1px solid #f1f5f9;
    }
    .subject-card-top h3 { font-size: 15px; font-weight: 700; color: var(--secondary); margin-bottom: 2px; }
    .subject-card-top p  { font-size: 12px; color: #94a3b8; }
    .subject-card-body { padding: 14px 18px; }
    .meta-row { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; margin-bottom: 8px; }
    .meta-row i { width: 16px; color: #94a3b8; }
    .subject-card-body .btn-primary { width: 100%; justify-content: center; margin-top: 12px; font-size: 13px; }

    /* Empty state */
    .empty-state { text-align: center; padding: 64px 20px; }
    .empty-state i { font-size: 56px; color: #e2e8f0; display: block; margin-bottom: 16px; }
    .empty-state p { color: #94a3b8; font-size: 15px; }

    @media (max-width: 600px) {
        .jadwal-header h1 { font-size: 20px; }
        .schedule-table { min-width: 500px; }
    }
</style>

<div class="jadwal-header">
    <h1><i class="fas fa-calendar-alt" style="color:var(--primary);margin-right:8px;"></i>Jadwal Pelajaran</h1>
    <p>Lihat jadwal mingguan kelas Anda</p>
</div>

@if($schedules->count())

    {{-- Weekly Table --}}
    <div class="card" style="margin-bottom:24px;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-table" style="color:var(--primary);margin-right:8px;"></i>Jadwal Mingguan</div>
        </div>
        <div class="card-body table-wrap" style="padding:0;">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Hari</th><th>Mata Pelajaran</th><th>Kelas</th><th>Pengajar</th><th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @php $hasAny = false; @endphp
                    @foreach($daysOrder as $day)
                        @php $daySchedules = $schedules->filter(fn($s) => strtolower($s->day_of_week) === $day)->sortBy('start_time'); @endphp
                        @foreach($daySchedules as $schedule)
                            @php $hasAny = true; $color = $dayColors[$day] ?? '#6366f1'; $class = $schedule->class; @endphp
                            <tr>
                                <td>
                                    @if($loop->first)
                                        <span class="day-badge" style="background:{{ $color }}">{{ $dayLabels[$day] ?? ucfirst($day) }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $class->classSubjects?->first()?->subject?->name ?? '—' }}</strong></td>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->classSubjects?->first()?->teacher?->name ?? '—' }}</td>
                                <td>
                                    @if($schedule->start_time)
                                        <span class="time-chip">
                                            <i class="fas fa-clock"></i>
                                            {{ \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') }}{{ $schedule->end_time ? ' – '.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : '' }}
                                        </span>
                                    @else
                                        <span style="color:#94a3b8">TBA</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    @if(!$hasAny)
                        <tr><td colspan="5"><div class="empty-state"><i class="fas fa-calendar"></i><p>Belum ada jadwal yang tersedia</p></div></td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Subject Cards --}}
    <div class="cards-grid">
        @foreach($schedules->sortBy(fn($s) => array_search(strtolower($s->day_of_week), $daysOrder)) as $schedule)
            @php $color = $dayColors[strtolower($schedule->day_of_week)] ?? '#6366f1'; $class = $schedule->class; @endphp
            <div class="subject-card">
                <div class="subject-card-top">
                    <div>
                        <h3>{{ $class->classSubjects?->first()?->subject?->name ?? 'Mata Pelajaran' }}</h3>
                        <p>{{ $class->name }}</p>
                    </div>
                    <span class="day-badge" style="background:{{ $color }};flex-shrink:0;">
                        {{ $dayLabels[strtolower($schedule->day_of_week)] ?? $schedule->day_of_week }}
                    </span>
                </div>
                <div class="subject-card-body">
                    <div class="meta-row"><i class="fas fa-chalkboard-teacher"></i> {{ $class->classSubjects?->first()?->teacher?->name ?? '—' }}</div>
                    @if($schedule->start_time)
                        <div class="meta-row"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') }}{{ $schedule->end_time ? ' – '.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : '' }}</div>
                    @endif
                    @if($schedule->room)
                        <div class="meta-row"><i class="fas fa-door-open"></i> {{ $schedule->room }}</div>
                    @endif
                    @if($class->description)
                        <div class="meta-row" style="align-items:flex-start;">
                            <i class="fas fa-info-circle" style="margin-top:2px;"></i>
                            <span style="color:#64748b;font-size:12px;">{{ Str::limit($class->description, 80) }}</span>
                        </div>
                    @endif
                    <a href="{{ route('siswa.subjects.show', $class->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right"></i> Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@else
    <div class="card">
        <div class="card-body empty-state">
            @if($classes->count() === 0)
                <i class="fas fa-calendar-times"></i>
                <p>Anda belum terdaftar di kelas apapun</p>
                <p style="font-size:13px;margin-top:6px;">Jadwal akan muncul setelah Anda terdaftar di kelas.</p>
            @else
                <i class="fas fa-clock"></i>
                <p>Belum ada jadwal yang ditetapkan</p>
                <p style="font-size:13px;margin-top:6px;">Hubungi guru atau admin untuk mengatur jadwal kelas Anda.</p>
            @endif
        </div>
    </div>
@endif

@endsection