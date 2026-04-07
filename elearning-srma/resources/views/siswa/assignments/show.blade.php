@extends('layouts.siswa')

@section('title', $assignment->title)
@section('icon', 'fas fa-tasks')

@section('content')
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-tasks"></i>
                    {{ $assignment->title }}
                </h1>
                <p class="page-description">{{ $assignment->eClass->subject->name }} • {{ $assignment->eClass->name }}</p>
            </div>
            <a href="{{ route('siswa.assignments.index') }}" style="color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @php
        $submission = \App\Models\Submission::where('student_id', auth()->id())
            ->where('assignment_id', $assignment->id)
            ->first();
        $deadline = $assignment->deadline;
        $isLate = $submission && $submission->submitted_at && $submission->submitted_at > $deadline;
        $isOverdue = now() > $deadline && !$submission->submitted_at;
    @endphp

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- MAIN CONTENT -->
        <div>
            <!-- Description Card -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-file-alt" style="color: var(--primary); margin-right: 10px;"></i>
                        Deskripsi Tugas
                    </div>
                </div>
                <div class="card-body">
                    <div style="color: #666; font-size: 14px; line-height: 1.6;">
                        {!! $assignment->description !!}
                    </div>
                </div>
            </div>

            <!-- Grade Card (if exists) -->
            @if($submission && $submission->grade)
                <div class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #fff3e0, #ffe0b2); border-left: 4px solid #f39c12;">
                    <div class="card-header" style="background: transparent; border-bottom: none;">
                        <div class="card-title" style="color: #e65100;">
                            <i class="fas fa-star" style="margin-right: 10px;"></i>
                            Penilaian Anda
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="text-align: center; padding: 20px;">
                            <div style="font-size: 48px; font-weight: 700; color: #f39c12; margin-bottom: 10px;">
                                {{ $submission->grade->score }}
                                @if($submission->grade->max_score)
                                    <span style="color: #999; font-size: 32px;">/ {{ $submission->grade->max_score }}</span>
                                @endif
                            </div>
                            @php
                                $maxScore = $submission->grade->max_score ?? 100;
                                $percentage = ($submission->grade->score / $maxScore) * 100;
                                $gradeLabel = $percentage >= 85 ? 'Sangat Baik' : ($percentage >= 70 ? 'Baik' : ($percentage >= 60 ? 'Cukup' : 'Kurang'));
                                $gradeColor = $percentage >= 85 ? '#28a745' : ($percentage >= 70 ? '#17a2b8' : ($percentage >= 60 ? '#ffc107' : '#dc3545'));
                            @endphp
                            <p style="color: {{ $gradeColor }}; font-weight: 600; font-size: 16px; margin-bottom: 10px;">
                                {{ $gradeLabel }}
                            </p>
                            <div style="background: #eee; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 15px;">
                                <div style="background: {{ $gradeColor }}; height: 100%; width: {{ $percentage }}%;"></div>
                            </div>
                            <p style="color: #666; font-size: 12px;">Persentase: {{ number_format($percentage, 1) }}%</p>
                        </div>

                        @if($submission->grade->feedback)
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.1);">
                                <p style="color: #999; font-size: 12px; text-transform: uppercase; margin-bottom: 8px; font-weight: 600;">Komentar Guru</p>
                                <p style="color: #333; font-size: 13px; line-height: 1.6;">{{ $submission->grade->feedback }}</p>
                            </div>
                        @endif

                        @if($submission->grade->graded_at)
                            <p style="color: #999; font-size: 11px; margin-top: 15px;">
                                <i class="fas fa-check-circle"></i>
                                Dinilai pada {{ $submission->grade->graded_at->format('d M Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Submission Files -->
            @if($submission && $submission->file_path)
                <div class="card" style="margin-bottom: 20px;">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-paperclip" style="color: #2980b9; margin-right: 10px;"></i>
                            File Pengumpulan Anda
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-file" style="font-size: 24px; color: #2980b9;"></i>
                                <div>
                                    <p style="font-weight: 600; color: var(--secondary); margin-bottom: 2px;">
                                        {{ basename($submission->file_path) }}
                                    </p>
                                    @if($submission->submitted_at)
                                        <p style="color: #999; font-size: 12px;">
                                            Dikirim: {{ $submission->submitted_at->format('d M Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submission Form (if not submitted or deadline not passed) -->
            @if(!$submission || !$submission->submitted_at || (now()->lessThan($deadline)))
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-cloud-upload-alt" style="color: #27ae60; margin-right: 10px;"></i>
                            Pengumpulan Tugas
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data" style="display: grid; gap: 15px;">
                            @csrf
                            
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--secondary);">
                                    <i class="fas fa-file-upload"></i> Unggah File
                                </label>
                                <input type="file" name="file" style="display: block; width: 100%; padding: 10px; border: 2px dashed var(--border); border-radius: 6px; cursor: pointer;" required>
                                <p style="color: #999; font-size: 12px; margin-top: 6px;">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, ZIP (Maksimal 10 MB)</p>
                            </div>

                            @if($submission && $submission->submitted_at && now()->lessThan($deadline))
                                <div style="background: #e3f2fd; padding: 10px; border-radius: 6px; border-left: 4px solid #2980b9;">
                                    <p style="color: #1565c0; font-size: 12px;">
                                        ℹ️ Anda dapat mengubah pengumpulan Anda sebelum deadline
                                    </p>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                                <i class="fas fa-paper-plane"></i> 
                                {{ $submission && $submission->submitted_at ? 'Perbarui Pengumpulan' : 'Kirim Pengumpulan' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- SIDEBAR -->
        <div>
            <!-- Status Card -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-info-circle" style="color: var(--primary); margin-right: 10px;"></i>
                        Status Tugas
                    </div>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                        <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 6px; font-weight: 600;">Status</p>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 12px; height: 12px; background: {{ $submission && $submission->submitted_at ? '#28a745' : ($isOverdue ? '#dc3545' : '#17a2b8') }}; border-radius: 50%;"></span>
                            <span style="color: var(--secondary); font-weight: 600;">
                                @if($submission && $submission->submitted_at)
                                    {{ $isLate ? '⚠️ Terlambat' : '✓ Terkumpul' }}
                                @elseif($isOverdue)
                                    ✗ Belum Dikumpul
                                @else
                                    ⏳ Draft
                                @endif
                            </span>
                        </div>
                    </div>

                    <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                        <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 6px; font-weight: 600;">Deadline</p>
                        <p style="color: var(--secondary); font-weight: 600;">{{ $deadline->format('d M Y') }}</p>
                        <p style="color: #666; font-size: 12px;">{{ $deadline->format('H:i') }} WIB</p>
                        <p style="color: {{ now()->greaterThan($deadline) ? '#dc3545' : '#f39c12' }}; font-size: 12px; font-weight: 600;">
                            @php
                                $now = now();
                                if ($now->greaterThan($deadline)) {
                                    echo '✗ Sudah terlewat';
                                } else {
                                    $days = $now->diffInDays($deadline, false);
                                    $hours = $now->diffInHours($deadline, false) % 24;
                                    if ($days > 0) {
                                        echo "⏰ " . $days . " hari " . $hours . " jam lagi";
                                    } else {
                                        echo "⏰ " . $hours . " jam lagi";
                                    }
                                }
                            @endphp
                        </p>
                    </div>

                    @if($submission && $submission->submitted_at)
                        <div style="margin-bottom: 15px;">
                            <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 6px; font-weight: 600;">Waktu Pengumpulan</p>
                            <p style="color: var(--secondary); font-weight: 600;">{{ $submission->submitted_at->format('d M Y') }}</p>
                            <p style="color: #666; font-size: 12px;">{{ $submission->submitted_at->format('H:i') }} WIB</p>
                            @if($isLate)
                                <p style="color: #ffc107; font-size: 11px; margin-top: 6px; font-weight: 600;">⚠️ Terlambat {{ $submission->submitted_at->diffInHours($deadline) }} jam</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Class Info Card -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-book" style="color: #2980b9; margin-right: 10px;"></i>
                        Informasi Kelas
                    </div>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                        <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 4px;">Mata Pelajaran</p>
                        <p style="color: var(--secondary); font-weight: 600;">{{ $assignment->eClass->subject->name }}</p>
                    </div>
                    
                    <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                        <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 4px;">Kelas</p>
                        <p style="color: var(--secondary); font-weight: 600;">{{ $assignment->eClass->name }}</p>
                    </div>

                    <div>
                        <p style="color: #999; font-size: 11px; text-transform: uppercase; margin-bottom: 4px;">Pengajar</p>
                        <p style="color: var(--secondary); font-weight: 600;">{{ $assignment->eClass->teacher->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
