@extends('layouts.guru')

@section('title', 'Detail Presensi - ' . $session->classSubject->eClass->name . ' - ' . $session->classSubject->subject->name)
@section('icon', 'fas fa-clipboard-list')

@section('content')
<style>
    .session-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
    }
    .session-header h1 { font-size: 24px; font-weight: 700; margin: 0 0 8px 0; }
    .session-header p { margin: 4px 0; opacity: 0.9; }
    
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 12px;
    }
    .status-badge.open { background: rgba(34,197,94,0.3); }
    .status-badge.closed { background: rgba(239,68,68,0.3); }
    .status-badge.cancelled { background: rgba(107,114,128,0.3); }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-top: 16px; }
    .stat-item { 
        background: rgba(255,255,255,0.1);
        padding: 12px;
        border-radius: 8px;
        text-align: center;
    }
    .stat-item .value { font-size: 24px; font-weight: 700; }
    .stat-item .label { font-size: 12px; opacity: 0.9; margin-top: 4px; }
    
    .action-buttons { margin-bottom: 24px; display: flex; gap: 12px; }
    .btn-action {
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-close { background: #f97316; color: white; }
    .btn-close:hover { background: #ea580c; }
    .btn-cancel { background: #dc2626; color: white; }
    .btn-cancel:hover { background: #b91c1c; }
    .btn-back { background: #f1f5f9; color: var(--secondary); }
    .btn-back:hover { background: #e2e8f0; }
    
    .attendance-table { width: 100%; border-collapse: collapse; }
    .attendance-table th {
        background: #f8fafc;
        padding: 12px;
        text-align: left;
        color: #64748b;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        border-bottom: 2px solid var(--border);
    }
    .attendance-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
    }
    .attendance-table tr:hover { background: #f8fafc; }
    
    .status-present { background: #f0fdf4; color: #166534; }
    .status-absent { background: #fef2f2; color: #991b1b; }
    .status-late { background: #fefce8; color: #854d0e; }
    .status-excused { background: #eff6ff; color: #0c4a6e; }
    
    .status-badge-small {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<div class="session-header">
    <h1>{{ $session->classSubject->eClass->name }} - {{ $session->classSubject->subject->name }}</h1>
    <p><i class="fas fa-calendar"></i> {{ $session->attendance_date->format('l, d F Y') }}</p>
    <p><i class="fas fa-clock"></i> Dibuka: {{ $session->opened_at }}</p>
    
    <span class="status-badge {{ strtolower($session->status) }}">
        @if($session->isOpen())
            <i class="fas fa-circle"></i> Terbuka
        @elseif($session->isClosed())
            <i class="fas fa-check-circle"></i> Ditutup
        @else
            <i class="fas fa-ban"></i> Dibatalkan
        @endif
    </span>

    <div class="stats-grid">
        <div class="stat-item">
            <div class="value">{{ $session->records->count() }}</div>
            <div class="label">Total Siswa</div>
        </div>
        <div class="stat-item">
            <div class="value">{{ $session->records->where('status', 'present')->count() }}</div>
            <div class="label">Hadir</div>
        </div>
        <div class="stat-item">
            <div class="value">{{ $session->records->where('status', 'absent')->count() }}</div>
            <div class="label">Tidak Hadir</div>
        </div>
        <div class="stat-item">
            <div class="value">{{ $session->getAttendancePercentage() }}%</div>
            <div class="label">Kehadiran</div>
        </div>
    </div>
</div>

@if($session->isOpen())
    <div class="action-buttons">
        <form action="{{ route('guru.attendance.close', $session) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-action btn-close" onclick="return confirm('Tutup presensi? Siswa tidak bisa absensi lagi.')">
                <i class="fas fa-times-circle"></i> Tutup Presensi
            </button>
        </form>
        <form action="{{ route('guru.attendance.cancel', $session) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-action btn-cancel" onclick="return confirm('Batalkan presensi? Data akan dihapus dan hanya admin bisa restore.')">
                <i class="fas fa-ban"></i> Batalkan Presensi
            </button>
        </form>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-users" style="color: var(--primary); margin-right: 8px;"></i>
            Daftar Absensi Siswa
        </div>
    </div>
    <div class="card-body" style="padding: 0; overflow-x: auto;">
        @if($session->records->count() > 0)
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Siswa</th>
                        <th>Status</th>
                        <th>Waktu Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($session->records->sortBy('student.name') as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="color: var(--secondary); font-weight: 500;">{{ $record->student->name }}</td>
                            <td>
                                <span class="status-badge-small status-{{ strtolower($record->status) }}">
                                    {{ $record->getStatusLabel() }}
                                </span>
                            </td>
                            <td style="color: #64748b; font-size: 13px;">
                                @if($record->checked_in_at)
                                    {{ $record->checked_in_at->format('H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                <i class="fas fa-inbox" style="font-size: 48px; display: block; margin-bottom: 15px;"></i>
                <p>Belum ada data presensi</p>
            </div>
        @endif
    </div>
</div>

<div style="margin-top: 24px;">
    <a href="{{ route('guru.attendance.index') }}" class="btn-action btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

@endsection
