@extends('layouts.guru')

@section('title', 'Presensi - Semua Mata Pelajaran')
@section('icon', 'fas fa-clipboard-list')

@section('content')
<style>
    .subject-section {
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-bottom: 24px;
        overflow: hidden;
    }
    
    .subject-header {
        background: linear-gradient(135deg, var(--primary) 0%, #743D52 100%);
        color: white;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .subject-title {
        font-size: 16px;
        font-weight: 600;
    }
    
    .subject-subtitle {
        font-size: 13px;
        opacity: 0.9;
        margin-top: 4px;
    }
    
    .subject-actions {
        display: flex;
        gap: 8px;
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
    
    .btn-add {
        background: rgba(255,255,255,0.25);
        color: white;
        border: 1px solid rgba(255,255,255,0.5);
    }
    
    .btn-add:hover {
        background: rgba(255,255,255,0.4);
    }
    
    .session-list {
        padding: 16px;
    }
    
    .session-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 4px;
        margin-bottom: 8px;
        background: #fafafa;
    }
    
    .session-info {
        flex: 1;
    }
    
    .session-date {
        font-weight: 600;
        color: var(--secondary);
        font-size: 14px;
    }
    
    .session-time {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }
    
    .session-meta {
        display: flex;
        gap: 16px;
        margin-top: 8px;
        font-size: 12px;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #64748b;
    }
    
    .meta-value {
        font-weight: 600;
        color: var(--secondary);
    }
    
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-open {
        background: #dcfce7;
        color: #166534;
    }
    
    .status-closed {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-cancelled {
        background: #f3f4f6;
        color: #374151;
    }
    
    .session-actions {
        display: flex;
        gap: 8px;
    }
    
    .btn-view {
        background: var(--primary);
        color: white;
    }
    
    .btn-view:hover {
        background: #4f46e5;
    }
    
    .btn-close {
        background: #f97316;
        color: white;
    }
    
    .btn-close:hover {
        background: #ea580c;
    }
    
    .empty-class {
        padding: 16px;
        text-align: center;
        color: #94a3b8;
        font-size: 13px;
    }
    
    .empty-all {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    
    .empty-all i {
        font-size: 56px;
        color: #e2e8f0;
        display: block;
        margin-bottom: 16px;
    }
</style>

<div style="margin-bottom: 30px;">
    <h1 class="page-title">
        <i class="fas fa-clipboard-list"></i>
        Presensi - Semua Mata Pelajaran
    </h1>
    <p class="page-description">Kelola presensi untuk semua mata pelajaran Anda</p>
</div>

@if(session('success'))
    <div style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($classSubjects->count() > 0)
    @foreach($classSubjects as $classSubject)
        <div class="subject-section">
            <div class="subject-header">
                <div>
                    <div class="subject-title">{{ $classSubject->subject->name }}</div>
                    <div class="subject-subtitle"><i class="fas fa-door-open"></i> {{ $classSubject->eClass->name }}</div>
                </div>
                <div class="subject-actions">
                    <a href="{{ route('guru.attendance.create') }}" data-subject="{{ $classSubject->id }}" class="btn-small btn-add btn-create-attendance">
                        <i class="fas fa-plus"></i> Buka Presensi
                    </a>
                </div>
            </div>

            <div class="session-list">
                @php
                    $subjectSessions = $sessions->filter(fn($s) => $s->class_subject_id === $classSubject->id)->take(5);
                @endphp

                @if($subjectSessions->count() > 0)
                    @foreach($subjectSessions as $session)
                        <div class="session-item">
                            <div class="session-info">
                                <div class="session-date">{{ $session->attendance_date->format('l, d F Y') }}</div>
                                <div class="session-time">
                                    <i class="fas fa-clock"></i> {{ $session->opened_at }}
                                    @if($session->closed_at)
                                        - {{ $session->closed_at }}
                                    @endif
                                </div>
                                <div class="session-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>Total: <span class="meta-value">{{ $session->records->count() }}</span></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-check"></i>
                                        <span>Hadir: <span class="meta-value">{{ $session->records->where('status', 'present')->count() }}</span></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-times"></i>
                                        <span>Tidak Hadir: <span class="meta-value">{{ $session->records->where('status', 'absent')->count() }}</span></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-percent"></i>
                                        <span>Kehadiran: <span class="meta-value">{{ $session->getAttendancePercentage() }}%</span></span>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="status-badge status-{{ strtolower($session->status) }}">
                                    @if($session->isOpen())
                                        <i class="fas fa-circle"></i> Terbuka
                                    @elseif($session->isClosed())
                                        <i class="fas fa-check-circle"></i> Ditutup
                                    @else
                                        <i class="fas fa-ban"></i> Dibatalkan
                                    @endif
                                </span>

                                <div class="session-actions">
                                    <a href="{{ route('guru.attendance.show', $session) }}" class="btn-small btn-view">
                                        <i class="fas fa-eye"></i> Lihat
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
                        </div>
                    @endforeach
                @else
                    <div class="empty-class">
                        <i class="fas fa-inbox"></i> Belum ada presensi untuk mata pelajaran ini
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@else
    <div class="empty-all">
        <i class="fas fa-inbox"></i>
        <p>Anda tidak memiliki mata pelajaran apapun</p>
    </div>
@endif

@endsection
