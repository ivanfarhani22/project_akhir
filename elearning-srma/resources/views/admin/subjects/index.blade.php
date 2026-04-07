@extends('layouts.admin')

@section('title', 'Kelola Mata Pelajaran')
@section('icon', 'book')

@section('content')
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="page-title">
                <i class="fas fa-book"></i>
                Manajemen Mata Pelajaran
            </h1>
            <p class="page-description">Kelola semua mata pelajaran di sekolah</p>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary" style="text-decoration: none;">
            <i class="fas fa-plus"></i> Tambah Mata Pelajaran
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card" style="margin-bottom: 25px;">
        <div class="card-body" style="padding: 20px;">
            <form method="GET" action="{{ route('admin.subjects.index') }}" style="display: flex; gap: 15px; align-items: flex-end;">
                <!-- Search Input -->
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary); font-size: 13px;">
                        <i class="fas fa-search"></i> Cari Mata Pelajaran
                    </label>
                    <input type="text" name="search" placeholder="Cari nama, kode, atau deskripsi..." 
                           value="{{ request('search') }}"
                           style="width: 100%; padding: 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px;">
                </div>

                <!-- Search Button -->
                <button type="submit" class="btn btn-primary" style="border: none; cursor: pointer;">
                    <i class="fas fa-search"></i> Cari
                </button>

                <!-- Reset Button -->
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary" style="text-decoration: none; border: none; cursor: pointer;">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>

            <!-- Info Text -->
            <small style="color: #999; margin-top: 10px; display: block;">
                Menampilkan {{ $subjects->count() }} dari {{ $subjects->total() }} mata pelajaran
            </small>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Mata Pelajaran</div>
        </div>
        <div class="card-body">
            @if($subjects->isEmpty())
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                    <h3 style="color: #999; margin-bottom: 10px;">Tidak ada mata pelajaran ditemukan</h3>
                    <p style="color: #bbb; margin-bottom: 20px;">
                        @if(request('search'))
                            Coba ubah pencarian Anda atau <a href="{{ route('admin.subjects.index') }}" style="color: var(--primary); text-decoration: none;">reset filter</a>
                        @else
                            Mulai dengan <a href="{{ route('admin.subjects.create') }}" style="color: var(--primary); text-decoration: none;">membuat mata pelajaran baru</a>
                        @endif
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 30%;">Nama Mata Pelajaran</th>
                                <th style="width: 15%;">Kode</th>
                                <th style="width: 35%;">Deskripsi</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                                <tr>
                                    <td>
                                        <span style="font-weight: 600; color: var(--secondary);">
                                            <i class="fas fa-book"></i> {{ $subject->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <code style="background: linear-gradient(135deg, #ffc107, #ff9800); color: white; padding: 6px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                            {{ $subject->code }}
                                        </code>
                                    </td>
                                    <td>
                                        <span style="color: #666; font-size: 13px;">{{ Str::limit($subject->description ?? '-', 60) }}</span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-sm" style="background: #0066cc; color: white; text-decoration: none; font-size: 12px; padding: 8px 12px;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm" style="background: #dc3545; color: white; border: none; cursor: pointer; font-size: 12px; padding: 8px 12px;">
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

                <!-- Pagination -->
                @if($subjects->hasPages())
                    <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border);">
                        <!-- Previous Button -->
                        @if($subjects->onFirstPage())
                            <button disabled class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed;">
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </button>
                        @else
                            <a href="{{ $subjects->previousPageUrl() }}" class="btn btn-secondary" style="text-decoration: none;">
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </a>
                        @endif

                        <!-- Page Info -->
                        <div style="padding: 8px 16px; background: #f5f5f5; border-radius: 8px; font-size: 13px; color: #666;">
                            Halaman {{ $subjects->currentPage() }} dari {{ $subjects->lastPage() }}
                        </div>

                        <!-- Next Button -->
                        @if($subjects->hasMorePages())
                            <a href="{{ $subjects->nextPageUrl() }}" class="btn btn-primary" style="text-decoration: none;">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled class="btn btn-primary" style="opacity: 0.5; cursor: not-allowed;">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>

    <style>
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        table thead tr {
            border-bottom: 2px solid var(--border);
            background: #f8f9fa;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--secondary);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        table tbody tr:hover {
            background: #fafafa;
        }

        table td {
            padding: 15px;
            color: #666;
            font-size: 14px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #5a5fd8;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 4px;
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--secondary);
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .page-title {
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--secondary);
        }

        .page-description {
            margin: 0;
            color: #999;
            font-size: 14px;
        }
    </style>
@endsection

