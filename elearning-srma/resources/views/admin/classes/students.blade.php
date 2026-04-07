@extends('layouts.admin')

@section('title', 'Kelola Siswa - ' . $class->name)
@section('icon', 'users')

@section('content')
<div class="content-area">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: #4A4A4A; margin: 0;">👥 Kelola Siswa Kelas</h1>
            <p style="color: #999; margin: 0.5rem 0 0 0;"><strong>{{ $class->name }}</strong> - {{ $class->subject->name ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('admin.classes.index') }}" class="btn" style="
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
        " onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">
            <i class="fas fa-info-circle"></i> {{ session('warning') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        {{-- Section 1: Tambah Siswa --}}
        <div class="card" style="
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid #28a745;
        ">
            <h3 style="color: #4A4A4A; margin: 0 0 1.5rem 0;">
                <i class="fas fa-plus-circle"></i> Tambah Siswa Baru
            </h3>

            <form method="POST" action="{{ route('admin.classes.students.store', $class) }}">
                @csrf
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="
                        display: block;
                        color: #4A4A4A;
                        font-weight: 600;
                        margin-bottom: 0.5rem;
                    ">Pilih Siswa:</label>
                    <select name="student_id" style="
                        width: 100%;
                        padding: 10px;
                        border: 2px solid #e0e0e0;
                        border-radius: 6px;
                        font-size: 0.95rem;
                        cursor: pointer;
                    " required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($allStudents as $student)
                            @if(!in_array($student->id, $enrolledStudentIds))
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} ({{ $student->email }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn" style="
                    width: 100%;
                    padding: 10px;
                    background: #28a745;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                    <i class="fas fa-plus"></i> Tambah Siswa
                </button>
            </form>
        </div>

        {{-- Section 2: Info Kelas --}}
        <div class="card" style="
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid #C41E3A;
        ">
            <h3 style="color: #4A4A4A; margin: 0 0 1.5rem 0;">
                <i class="fas fa-info-circle"></i> Informasi Kelas
            </h3>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <p style="color: #999; margin: 0; font-size: 0.9rem;"><strong>Nama Kelas:</strong></p>
                    <p style="color: #4A4A4A; margin: 0.3rem 0 0 0;">{{ $class->name }}</p>
                </div>
                <div>
                    <p style="color: #999; margin: 0; font-size: 0.9rem;"><strong>Mata Pelajaran:</strong></p>
                    <p style="color: #4A4A4A; margin: 0.3rem 0 0 0;">{{ $class->subject->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p style="color: #999; margin: 0; font-size: 0.9rem;"><strong>Guru Pengampu:</strong></p>
                    <p style="color: #4A4A4A; margin: 0.3rem 0 0 0;">{{ $class->teacher->name }}</p>
                </div>
                <div>
                    <p style="color: #999; margin: 0; font-size: 0.9rem;"><strong>Total Siswa:</strong></p>
                    <p style="color: #4A4A4A; margin: 0.3rem 0 0 0; font-size: 1.5rem; font-weight: bold;">{{ $students->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Siswa --}}
    <div class="card" style="
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    ">
        <div style="padding: 1.5rem; border-bottom: 2px solid #e0e0e0;">
            <h3 style="color: #4A4A4A; margin: 0;">
                <i class="fas fa-list"></i> Daftar Siswa ({{ $students->total() }})
            </h3>
        </div>

        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #e0e0e0;">
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #4A4A4A;">No.</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #4A4A4A;">Nama Siswa</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #4A4A4A;">Email</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #4A4A4A;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            <tr style="border-bottom: 1px solid #e0e0e0; transition: background 0.2s ease;" 
                                onmouseover="this.style.background='#f8f9fa'" 
                                onmouseout="this.style.background='white'">
                                <td style="padding: 15px; color: #666;">{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                                <td style="padding: 15px;">
                                    <strong style="color: #4A4A4A;">{{ $student->name }}</strong>
                                </td>
                                <td style="padding: 15px; color: #666;">{{ $student->email }}</td>
                                <td style="padding: 15px; text-align: center;">
                                    <form method="POST" action="{{ route('admin.classes.students.destroy', [$class, $student]) }}" 
                                        style="display: inline;" 
                                        onsubmit="return confirm('Hapus {{ $student->name }} dari kelas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm" style="
                                            padding: 6px 12px;
                                            background: #dc3545;
                                            color: white;
                                            border: none;
                                            border-radius: 4px;
                                            cursor: pointer;
                                            font-weight: 600;
                                            font-size: 0.85rem;
                                            transition: all 0.3s ease;
                                        " onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div style="padding: 1.5rem; border-top: 1px solid #e0e0e0; display: flex; justify-content: center;">
                {{ $students->links() }}
            </div>
        @else
            <div style="padding: 3rem; text-align: center; color: #999;">
                <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Belum ada siswa di kelas ini. Silakan tambahkan siswa terlebih dahulu!</p>
            </div>
        @endif
    </div>

</div>

<style>
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffc107;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    @media (max-width: 768px) {
        [style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }

        th, td {
            padding: 10px !important;
            font-size: 0.9rem;
        }

        .btn-sm {
            display: block;
            width: 100%;
        }
    }
</style>
@endsection
