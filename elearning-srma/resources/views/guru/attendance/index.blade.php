@extends('layouts.guru')

@section('title', 'Presensi - ' . $class->name)
@section('icon', 'fas fa-clipboard-list')

@section('content')
<style>
    .session-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        transition: box-shadow 0.2s;
    }
    .session-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    
    .session-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }
    .session-title { font-weight: 600; color: var(--secondary); }
    .session-date { color: #64748b; font-size: 13px; }
    
    .session-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }
    .stat { text-align: center; }
    .stat-value { font-size: 18px; font-weight: 700; color: var(--primary); }
    .stat-label { font-size: 11px; color: #64748b; text-transform: uppercase; margin-top: 4px; }
    
    .session-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-small {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-view { background: var(--primary); color: white; }
    .btn-view:hover { background: #4f46e5; }
    .btn-close { background: #f97316; color: white; }
    .btn-close:hover { background: #ea580c; }
    
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-open { background: #dcfce7; color: #166534; }
    .status-closed { background: #fee2e2; color: #991b1b; }
    .status-cancelled { background: #f3f4f6; color: #374151; }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .empty-state i { font-size: 56px; color: #e2e8f0; display: block; margin-bottom: 16px; }
</style>

<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title">
            <i class="fas fa-clipboard-list"></i>
            Presensi
        </h1>
        <p class="page-description">{{ $class->name }}</p>
    </div>
    <a href="{{ route('guru.attendance.create', ['class_id' => $class->id]) }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buka Presensi
    </a>
</div>

@if(session('success'))
    <div style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($sessions->count() > 0)
    <div style="margin-bottom: 24px;">
        @foreach($sessions as $session)
            <div class="session-card">
                <div class="session-header">
                    <div>
                        <div class="session-title">{{ $session->attendance_date->format('l, d F Y') }}</div>
                        <div class="session-date">Jam: {{ $session->opened_at }} @if($session->closed_at)- {{ $session->closed_at }}@endif</div>
                    </div>
                    <span class="status-badge status-{{ strtolower($session->status) }}">
                        @if($session->isOpen())
                            <i class="fas fa-circle"></i> Terbuka
                        @elseif($session->isClosed())
                            <i class="fas fa-check-circle"></i> Ditutup
                        @else
                            <i class="fas fa-ban"></i> Dibatalkan
                        @endif
                    </span>
                </div>

                <div class="session-stats">
                    <div class="stat">
                        <div class="stat-value">{{ $session->records->count() }}</div>
                        <div class="stat-label">Total</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">{{ $session->records->where('status', 'present')->count() }}</div>
                        <div class="stat-label">Hadir</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">{{ $session->records->where('status', 'absent')->count() }}</div>
                        <div class="stat-label">Tidak Hadir</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">{{ $session->getAttendancePercentage() }}%</div>
                        <div class="stat-label">Kehadiran</div>
                    </div>
                </div>

                <div class="session-actions">
                    <a href="{{ route('guru.attendance.show', $session) }}" class="btn-small btn-view">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    @if($session->isOpen())
                        <form action="{{ route('guru.attendance.close', $session) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-small btn-close" onclick="return confirm('Tutup presensi?')">
                                <i class="fas fa-times-circle"></i> Tutup
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($sessions->hasPages())
        <div style="display: flex; justify-content: center; margin-top: 24px;">
            {{ $sessions->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>Belum ada presensi untuk kelas ini</p>
        <p style="font-size: 13px; margin-top: 8px;">
            <a href="{{ route('guru.attendance.create', ['class_id' => $class->id]) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                Buka presensi sekarang
            </a>
        </p>
    </div>
@endif

@endsection
