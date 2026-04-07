@extends('layouts.siswa')

@section('title', 'Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-tasks"></i>
            Tugas
        </h1>
        <p class="page-description">Kelola tugas, pengumpulan, dan nilai Anda</p>
    </div>

    @if($assignments->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            @foreach($assignments as $submission)
                @php
                    $assignment = $submission->assignment;
                    $deadline = $assignment->deadline;
                    $isLate = $submission->submitted_at && $submission->submitted_at > $deadline;
                    $isOverdue = now() > $deadline && !$submission->submitted_at;
                @endphp
                <div class="card">
                    <div class="card-header">
                        <div style="flex: 1;">
                            <h3 style="font-size: 16px; font-weight: 600; color: var(--secondary); margin-bottom: 4px;">
                                {{ $assignment->title }}
                            </h3>
                            <p style="color: #999; font-size: 12px;">{{ $assignment->eClass->subject->name }}</p>
                        </div>
                        <span style="background: {{ $submission->submitted_at ? '#28a745' : ($isOverdue ? '#dc3545' : '#17a2b8') }}; color: white; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; white-space: nowrap;">
                            @if($submission->submitted_at)
                                ✓ {{ $isLate ? 'Terlambat' : 'Terkumpul' }}
                            @elseif($isOverdue)
                                ✗ Belum Dikumpul
                            @else
                                ⏳ Draft
                            @endif
                        </span>
                    </div>
                    
                    <div class="card-body">
                        <!-- Deskripsi -->
                        @if($assignment->description)
                            <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                                <p style="color: #666; font-size: 13px;">{{ Str::limit($assignment->description, 150) }}</p>
                            </div>
                        @endif

                        <!-- Info Row -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                            <div>
                                <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 4px;">Deadline</p>
                                <p style="color: var(--secondary); font-weight: 600; font-size: 12px;">{{ $deadline->format('d M Y') }}</p>
                                <p style="color: #f39c12; font-size: 11px;">{{ $deadline->format('H:i') }}</p>
                            </div>
                            <div>
                                <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 4px;">Status Pengumpulan</p>
                                @if($submission->submitted_at)
                                    <p style="color: #28a745; font-weight: 600; font-size: 12px;">✓ {{ $submission->submitted_at->format('d M Y') }}</p>
                                    <p style="color: #666; font-size: 11px;">{{ $submission->submitted_at->format('H:i') }}</p>
                                @else
                                    <p style="color: #dc3545; font-weight: 600; font-size: 12px;">Belum Dikumpul</p>
                                @endif
                            </div>
                        </div>

                        <!-- Grade Section -->
                        @if($submission->grade)
                            <div style="background: linear-gradient(135deg, #fff3e0, #ffe0b2); padding: 12px; border-radius: 6px; margin-bottom: 15px; border-left: 4px solid #f39c12;">
                                <p style="color: #e65100; font-size: 11px; text-transform: uppercase; margin-bottom: 4px; font-weight: 600;">Nilai / Skor</p>
                                <div style="display: flex; align-items: baseline; gap: 10px;">
                                    <span style="font-size: 32px; font-weight: 700; color: #f39c12;">{{ $submission->grade->score }}</span>
                                    @if($submission->grade->max_score)
                                        <span style="color: #999; font-size: 14px;">/ {{ $submission->grade->max_score }}</span>
                                    @endif
                                </div>
                                @if($submission->grade->feedback)
                                    <p style="color: #666; font-size: 12px; margin-top: 8px;">{{ $submission->grade->feedback }}</p>
                                @endif
                            </div>
                        @else
                            <div style="background: #f5f5f5; padding: 12px; border-radius: 6px; margin-bottom: 15px; text-align: center;">
                                <p style="color: #999; font-size: 12px;">Menunggu Penilaian</p>
                            </div>
                        @endif

                        <!-- Files Section -->
                        @if($submission->file_path)
                            <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                                <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 8px; font-weight: 600;">File Pengumpulan</p>
                                <a href="#" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center;">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-check-circle" style="font-size: 64px; color: #ddd; margin-bottom: 20px; display: block;"></i>
                <p style="color: #999; font-size: 16px;">Anda belum memiliki tugas</p>
            </div>
        </div>
    @endif
@endsection
