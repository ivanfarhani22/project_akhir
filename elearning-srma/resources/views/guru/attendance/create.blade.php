@extends('layouts.guru')

@section('title', 'Buka Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')
<style>
    .form-group { margin-bottom: 20px; }
    .form-group label { 
        display: block;
        margin-bottom: 8px;
        color: var(--secondary);
        font-weight: 600;
        font-size: 14px;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    .btn-submit {
        background: var(--primary);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s;
    }
    .btn-submit:hover { background: #4f46e5; }
    .btn-cancel {
        background: #f1f5f9;
        color: var(--secondary);
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-cancel:hover { background: #e2e8f0; }
</style>

<div style="margin-bottom: 30px;">
    <h1 class="page-title">
        <i class="fas fa-clipboard-list"></i>
        Buka Presensi
    </h1>
    <p class="page-description">Pilih mata pelajaran dan atur waktu presensi</p>
</div>

@if($errors->any())
    <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px;">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin: 8px 0 0 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('guru.attendance.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="class_subject_id">Mata Pelajaran</label>
                <select id="class_subject_id" name="class_subject_id" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($classSubjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('class_subject_id') == $subject->id)>
                            {{ $subject->eClass->name }} - {{ $subject->subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="attendance_date">Tanggal Presensi</label>
                <input type="date" id="attendance_date" name="attendance_date" value="{{ old('attendance_date', today()->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="opened_at">Jam Buka Presensi</label>
                <input type="time" id="opened_at" name="opened_at" value="{{ old('opened_at', now()->format('H:i')) }}" required>
            </div>

            <div class="form-group">
                <label for="notes">Catatan (Opsional)</label>
                <textarea id="notes" name="notes" placeholder="Contoh: Presensi untuk topik Geometri Ruang">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i> Buka Presensi
                </button>
                <a href="{{ route('guru.attendance.index') }}" class="btn-cancel">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
