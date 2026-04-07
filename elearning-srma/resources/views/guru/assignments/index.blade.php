@extends('layouts.guru')

@section('title', 'Manajemen Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Kelola Tugas</p>
            <h1 class="page-title">
                <i class="fas fa-tasks"></i>
                Manajemen Tugas Pembelajaran
            </h1>
            <p class="page-description">Kelas: <strong>{{ $class->name }}</strong></p>
        </div>
        <a href="{{ route('guru.assignments.create', ['class_id' => $class->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Tugas
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Tugas</div>
            <span style="background: #f0f0f0; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                Total: {{ $assignments->count() }}
            </span>
        </div>
        <div class="card-body">
            @if($assignments->isEmpty())
                <div style="text-align: center; padding: 60px 20px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                    <p style="font-size: 16px;">Belum ada tugas</p>
                    <a href="{{ route('guru.assignments.create', ['class_id' => $class->id]) }}" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Buat Tugas Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 30%;">Judul Tugas</th>
                                <th style="width: 20%;">Deadline</th>
                                <th style="width: 15%;">Submission</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td>
                                        <strong style="color: var(--secondary);">{{ Str::limit($assignment->title, 40) }}</strong>
                                        @if($assignment->file_path)
                                            <div style="margin-top: 5px; font-size: 12px; color: #999;">
                                                <i class="fas fa-paperclip"></i> File tersedia
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-weight: 600;">{{ $assignment->deadline->format('d M Y') }}</span>
                                        <div style="font-size: 12px; color: #999; margin-top: 3px;">
                                            @if($assignment->deadline->isPast())
                                                <span class="badge" style="background-color: #f8d7da; color: #721c24;">Sudah Lewat</span>
                                            @elseif($assignment->deadline->diffInDays() <= 2)
                                                <span class="badge" style="background-color: #fff3cd; color: #856404;">Segera</span>
                                            @else
                                                <span class="badge" style="background-color: #d1ecf1; color: #0c5460;">Aktif</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="font-size: 20px; font-weight: 700; color: #0066cc;">
                                            {{ $assignment->submissions()->whereNotNull('submitted_at')->count() }}
                                        </div>
                                        <div style="font-size: 12px; color: #999;">
                                            dari {{ $class->students->count() }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $submitted = $assignment->submissions()->whereNotNull('submitted_at')->count();
                                            $total = $class->students->count();
                                            $percentage = $total > 0 ? round(($submitted / $total) * 100) : 0;
                                        @endphp
                                        <div style="width: 100%; height: 6px; background: #eee; border-radius: 3px; overflow: hidden; margin-bottom: 5px;">
                                            <div style="width: {{ $percentage }}%; height: 100%; background: linear-gradient(90deg, #28a745, #20c997);"></div>
                                        </div>
                                        <span style="font-size: 12px; color: #666;">{{ $percentage }}% dikumpul</span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px;">
                                            <a href="{{ route('guru.assignments.show', $assignment) }}" class="btn btn-sm" style="background: #17a2b8; color: white; text-decoration: none; font-size: 11px;">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('guru.assignments.edit', $assignment) }}" class="btn btn-sm" style="background: #0066cc; color: white; text-decoration: none; font-size: 11px;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('guru.assignments.destroy', $assignment) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm" style="background: #dc3545; color: white; border: none; cursor: pointer; font-size: 11px;">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- BACK BUTTON -->
    <div style="margin-top: 30px;">
        <a href="{{ route('guru.dashboard') }}" class="btn btn-secondary" style="text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
@endsection
