@extends('layouts.guru')

@section('title', 'Nilai Siswa')
@section('icon', 'fas fa-star')

@section('content')
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-star"></i>
            Nilai Siswa
        </h1>
        <p class="page-description">Kelola nilai dan penilaian siswa</p>
    </div>

    <!-- Filter Assignment -->
    @if($assignments->count() > 0)
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-body">
                <p style="color: #666; font-size: 13px; margin-bottom: 10px;">Filter berdasarkan tugas:</p>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('guru.grades.index') }}" class="btn btn-secondary btn-sm" style="background: var(--primary); color: white;">
                        <i class="fas fa-list"></i> Semua Penilaian
                    </a>
                    @foreach($assignments as $assignment)
                        <a href="{{ route('guru.grades.index', ['assignment_id' => $assignment->id]) }}" class="btn btn-secondary btn-sm">
                            {{ Str::limit($assignment->title, 30) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Penilaian</div>
            <span style="background: #f0f0f0; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                Total: {{ $submissions->count() }}
            </span>
        </div>
        <div class="table-responsive">
            @if($submissions->isEmpty())
                <div style="text-align: center; padding: 60px 20px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                    <p style="font-size: 16px;">Belum ada pengumpulan</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tugas</th>
                            <th>Status</th>
                            <th>Nilai</th>
                            <th>Tanggal Dinilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $submission)
                            @php
                                $grade = $submission->grade;
                                $gradeColor = !$grade ? '#6c757d' : ($grade->score >= 80 ? '#28a745' : ($grade->score >= 70 ? '#ffc107' : '#dc3545'));
                            @endphp
                            <tr>
                                <td style="font-weight: 600;">{{ $submission->student->name }}</td>
                                <td>{{ Str::limit($submission->assignment->title, 30) }}</td>
                                <td>
                                    @if($submission->submitted_at)
                                        <span style="background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 4px; font-size: 12px;">
                                            Terkumpul
                                        </span>
                                    @else
                                        <span style="background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 4px; font-size: 12px;">
                                            Belum Dikumpul
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($grade)
                                        <span style="background: {{ $gradeColor }}; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                            {{ $grade->score }}
                                        </span>
                                    @else
                                        <span style="color: #999;">-</span>
                                    @endif
                                </td>
                                <td style="font-size: 13px;">
                                    {{ $grade && $grade->graded_at ? $grade->graded_at->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    <a href="{{ route('guru.grades.edit', $submission) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Nilai
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
