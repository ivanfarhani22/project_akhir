@extends('layouts.guru')

@section('title', 'Nilai Tugas')
@section('icon', 'fas fa-star')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Penilaian</p>
        <h1 class="page-title">
            <i class="fas fa-star"></i>
            Beri Nilai Tugas
        </h1>
        <p class="page-description">
            Tugas: <strong>{{ $assignment->title }}</strong> • 
            Siswa: <strong>{{ $submission->student->name }}</strong>
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <!-- Info Submission -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-file-check" style="color: var(--primary); margin-right: 10px;"></i>
                    Informasi Submission
                </div>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <p style="color: #999; font-size: 12px; margin: 0 0 5px;">Status</p>
                    @if($submission->submitted_at)
                        <div style="background: #d4edda; color: #155724; padding: 8px 12px; border-radius: 6px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> Dikumpulkan
                        </div>
                    @else
                        <div style="background: #fff3cd; color: #856404; padding: 8px 12px; border-radius: 6px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-clock"></i> Belum Dikumpulkan
                        </div>
                    @endif
                </div>

                @if($submission->submitted_at)
                    <div style="margin-bottom: 15px;">
                        <p style="color: #999; font-size: 12px; margin: 0 0 5px;">Waktu Pengumpulan</p>
                        <p style="color: var(--secondary); font-weight: 600; margin: 0; font-size: 14px;">
                            {{ $submission->submitted_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    @if($submission->submitted_at > $assignment->deadline)
                        <div style="background: #f8d7da; color: #721c24; padding: 8px 12px; border-radius: 6px; font-size: 12px; margin-bottom: 15px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Terlambat!</strong> Dikumpulkan {{ $submission->submitted_at->diffForHumans($assignment->deadline) }}
                        </div>
                    @endif
                @endif

                @if($submission->file_path)
                    <div>
                        <p style="color: #999; font-size: 12px; margin: 0 0 5px;">File</p>
                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; background: var(--primary); color: white; text-decoration: none; border-radius: 6px; font-size: 12px;">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Tugas -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-info-circle" style="color: var(--primary); margin-right: 10px;"></i>
                    Informasi Tugas
                </div>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <p style="color: #999; font-size: 12px; margin: 0 0 5px;">Batas Waktu</p>
                    <p style="color: var(--secondary); font-weight: 600; margin: 0; font-size: 14px;">
                        {{ $assignment->deadline->format('d M Y H:i') }}
                    </p>
                </div>

                <div>
                    <p style="color: #999; font-size: 12px; margin: 0 0 5px;">Deskripsi</p>
                    <p style="color: var(--secondary); margin: 0; font-size: 13px; line-height: 1.5;">
                        {{ $assignment->description ?: 'Tidak ada deskripsi' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Nilai -->
    <div class="card" style="max-width: 600px;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-pen-to-square" style="color: var(--primary); margin-right: 10px;"></i>
                Form Penilaian
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('guru.grades.update', $submission) }}">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Nilai (0-100) *
                    </label>
                    <input 
                        type="number" 
                        name="score" 
                        id="score"
                        min="0"
                        max="100"
                        step="1"
                        class="form-input"
                        value="{{ old('score', $submission->grade->score ?? '') }}" 
                        placeholder="Masukkan nilai 0-100"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                        required
                    >
                    <small style="color: #999; margin-top: 5px; display: block;">Masukkan nilai siswa untuk tugas ini</small>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Komentar/Feedback
                    </label>
                    <textarea 
                        name="feedback" 
                        id="feedback"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease; font-family: inherit; resize: vertical;"
                        rows="4"
                        placeholder="Berikan feedback atau komentar untuk siswa..."
                    >{{ old('feedback', $submission->grade->feedback ?? '') }}</textarea>
                    <small style="color: #999; margin-top: 5px; display: block;">Feedback akan ditampilkan kepada siswa</small>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button 
                        type="submit" 
                        class="btn btn-primary"
                        style="flex: 1; justify-content: center;"
                    >
                        <i class="fas fa-save"></i> Simpan Nilai
                    </button>
                    <a 
                        href="{{ route('guru.grades.index', ['assignment_id' => $assignment->id]) }}" 
                        class="btn btn-secondary"
                        style="flex: 1; justify-content: center; text-decoration: none;"
                    >
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
        }
    </style>
@endsection
