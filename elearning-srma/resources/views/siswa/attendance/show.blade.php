@extends('layouts.siswa')

@section('title', 'Presensi - ' . $classSubject->subject->name)
@section('icon', 'fas fa-clipboard-list')

@section('content')
<style>
    .attendance-card { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .attendance-card h2 { font-size: 20px; font-weight: 700; margin-bottom: 8px; }
    .attendance-card .status-badge {
        display: inline-block;
        padding: 6px 14px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 12px;
    }
    .attendance-card .status-badge.open { background: rgba(34,197,94,0.3); }
    .attendance-card .status-badge.closed { background: rgba(239,68,68,0.3); }
    .attendance-card .status-badge.attended { background: rgba(34,197,94,0.4); }
    
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
    .info-item { background: #f8fafc; padding: 16px; border-radius: 8px; }
    .info-item label { color: #64748b; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 4px; }
    .info-item .value { color: var(--secondary); font-weight: 600; font-size: 16px; }
    
    .btn-attend { 
        background: #22c55e;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: background 0.2s;
    }
    .btn-attend:hover { background: #16a34a; }
    .btn-attend:disabled { background: #cbd5e1; cursor: not-allowed; }
    
    .success-message {
        background: #f0fdf4;
        border-left: 4px solid #22c55e;
        color: #166534;
        padding: 12px 16px;
        border-radius: 4px;
        margin-bottom: 16px;
    }
    .error-message {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 4px;
        margin-bottom: 16px;
    }
</style>

<div style="margin-bottom: 30px;">
    <h1 class="page-title">
        <i class="fas fa-clipboard-list"></i>
        Presensi
    </h1>
    <p class="page-description">{{ $classSubject->subject->name }} • {{ $classSubject->eClass->name }}</p>
</div>

@if(session('success'))
    <div class="success-message">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="error-message">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
@endif

@if($session)
    <div class="attendance-card">
        <h2>Presensi Hari Ini</h2>
        <p style="margin: 8px 0; opacity: 0.9;">{{ $session->attendance_date->format('l, d F Y') }}</p>
        
        <div class="status-badge open">
            <i class="fas fa-circle"></i> Presensi Terbuka
        </div>
        
        <div class="info-grid" style="margin-top: 16px;">
            <div class="info-item">
                <label>Waktu Dibuka</label>
                <div class="value">{{ $session->opened_at }}</div>
            </div>
            <div class="info-item">
                <label>Status Anda</label>
                <div class="value">
                    @if($hasAttended)
                        <span style="color: #22c55e;"><i class="fas fa-check-circle"></i> Sudah Hadir</span>
                    @else
                        <span style="color: #f59e0b;"><i class="fas fa-clock"></i> Belum Hadir</span>
                    @endif
                </div>
            </div>
        </div>

        @if(!$hasAttended)
            <form action="{{ route('siswa.attendance.store', $session) }}" method="POST">
                @csrf
                <button type="submit" class="btn-attend">
                    <i class="fas fa-check"></i> Lakukan Absensi Sekarang
                </button>
            </form>
        @else
            <div style="background: rgba(34,197,94,0.1); padding: 12px; border-radius: 8px; text-align: center;">
                <i class="fas fa-check-circle" style="color: #22c55e; font-size: 24px; display: block; margin-bottom: 8px;"></i>
                <p style="margin: 0; color: #22c55e; font-weight: 600;">Absensi Anda Tercatat</p>
            </div>
        @endif
    </div>
@else
    <div class="card">
        <div class="card-body" style="text-align: center; padding: 48px 20px;">
            <i class="fas fa-inbox" style="font-size: 56px; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
            <p style="color: #64748b; font-size: 16px; margin-bottom: 8px;">Belum Ada Presensi Hari Ini</p>
            <p style="color: #94a3b8; font-size: 13px;">Guru akan membuka presensi saat pelajaran dimulai</p>
        </div>
    </div>
@endif

<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-history" style="color: var(--primary); margin-right: 8px;"></i>
            Riwayat Presensi
        </div>
    </div>
    <div class="card-body">
        @php
            $allSessions = $classSubject->attendanceSessions()
                ->where('status', '!=', 'cancelled')
                ->with('records')
                ->orderBy('attendance_date', 'desc')
                ->take(10)
                ->get();
        @endphp

        @if($allSessions->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th style="padding: 12px; text-align: left; color: #64748b; font-weight: 600; font-size: 12px; text-transform: uppercase;">Tanggal</th>
                            <th style="padding: 12px; text-align: left; color: #64748b; font-weight: 600; font-size: 12px; text-transform: uppercase;">Status</th>
                            <th style="padding: 12px; text-align: left; color: #64748b; font-weight: 600; font-size: 12px; text-transform: uppercase;">Waktu Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allSessions as $sess)
                            @php
                                $record = $sess->records->where('student_id', auth()->id())->first();
                            @endphp
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 12px; color: var(--secondary);">
                                    {{ $sess->attendance_date->format('d M Y') }}
                                </td>
                                <td style="padding: 12px;">
                                    @if($record)
                                        @php
                                            $statusColors = [
                                                'present' => ['bg' => '#f0fdf4', 'text' => '#166534', 'label' => 'Hadir'],
                                                'absent' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'label' => 'Tidak Hadir'],
                                                'late' => ['bg' => '#fefce8', 'text' => '#854d0e', 'label' => 'Terlambat'],
                                                'excused' => ['bg' => '#eff6ff', 'text' => '#0c4a6e', 'label' => 'Izin'],
                                            ];
                                            $colors = $statusColors[$record->status] ?? ['bg' => '#f1f5f9', 'text' => '#1e293b', 'label' => 'Unknown'];
                                        @endphp
                                        <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            {{ $colors['label'] }}
                                        </span>
                                    @else
                                        <span style="color: #94a3b8; font-size: 12px;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; color: #64748b; font-size: 13px;">
                                    @if($record && $record->checked_in_at)
                                        {{ $record->checked_in_at->format('H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="text-align: center; color: #94a3b8; padding: 24px 0;">Belum ada riwayat presensi</p>
        @endif
    </div>
</div>

@endsection
