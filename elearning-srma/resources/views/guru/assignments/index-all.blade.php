@extends('layouts.guru')

@section('title', 'Manajemen Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="page-title">
                <i class="fas fa-tasks"></i>
                Manajemen Tugas
            </h1>
            <p class="page-description">Kelola semua tugas dan soal Anda</p>
        </div>
        <a href="{{ route('guru.assignments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tugas Baru
        </a>
    </div>

    <!-- Filter Kelas -->
    @if($classes->count() > 0)
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-body">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('guru.assignments.index') }}" class="btn btn-secondary btn-sm" style="background: var(--primary); color: white;">
                        <i class="fas fa-list"></i> Semua Tugas
                    </a>
                    @foreach($classes as $class)
                        <a href="{{ route('guru.assignments.index', ['class_id' => $class->id]) }}" class="btn btn-secondary btn-sm">
                            {{ $class->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Tugas</div>
            <span style="background: #f0f0f0; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                Total: {{ $assignments->count() }}
            </span>
        </div>
        <div class="table-responsive">
            @if($assignments->isEmpty())
                <div style="text-align: center; padding: 60px 20px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                    <p style="font-size: 16px;">Belum ada tugas</p>
                    <a href="{{ route('guru.assignments.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Buat Tugas Pertama
                    </a>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Judul Tugas</th>
                            <th>Deadline</th>
                            <th>Pengumpulan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            @php
                                $submissions = $assignment->submissions;
                                $totalSubmissions = $submissions->count();
                                $isDeadlinePassed = $assignment->deadline < now();
                            @endphp
                            <tr>
                                <td style="font-weight: 600;">{{ $assignment->eClass->name }}</td>
                                <td>{{ Str::limit($assignment->title, 40) }}</td>
                                <td>
                                    <span style="background: {{ $isDeadlinePassed ? '#ff6b6b' : '#ffc107' }}; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px;">
                                        {{ $assignment->deadline->format('d M Y H:i') }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="background: #e3f2fd; color: #1976d2; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                        {{ $totalSubmissions }}/{{ $assignment->eClass->students->count() }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('guru.assignments.edit', $assignment) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('guru.assignments.destroy', $assignment) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
