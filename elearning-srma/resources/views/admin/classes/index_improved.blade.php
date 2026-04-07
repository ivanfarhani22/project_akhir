@extends('layouts.admin')

@section('title', 'Kelola Kelas')
@section('icon', 'fas fa-chalkboard')

@section('content')
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="page-title">
                <i class="fas fa-chalkboard"></i>
                Kelola Kelas
            </h1>
            <p class="page-description">Atur kelas, mata pelajaran, guru, dan siswa dengan mudah</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary" style="text-decoration: none;">
            <i class="fas fa-plus"></i> Tambah Kelas
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card" style="margin-bottom: 25px;">
        <div class="card-body" style="padding: 20px;">
            <form method="GET" action="{{ route('admin.classes.index') }}" style="display: flex; gap: 15px; align-items: flex-end;">
                <!-- Search Input -->
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary); font-size: 13px;">
                        <i class="fas fa-search"></i> Cari Kelas
                    </label>
                    <input type="text" name="search" placeholder="Cari nama kelas..." 
                           value="{{ request('search') }}"
                           style="width: 100%; padding: 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px;">
                </div>

                <!-- Search Button -->
                <button type="submit" class="btn btn-primary" style="border: none; cursor: pointer;">
                    <i class="fas fa-search"></i> Cari
                </button>

                <!-- Reset Button -->
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary" style="text-decoration: none; border: none; cursor: pointer;">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>

            <!-- Info Text -->
            <small style="color: #999; margin-top: 10px; display: block;">
                Menampilkan {{ $classes->count() }} dari {{ $classes->total() }} kelas
            </small>
        </div>
    </div>

    <!-- No Results Message -->
    @if($classes->isEmpty())
        <div style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></i>
            <h3 style="color: #999; margin-bottom: 10px;">Tidak ada kelas ditemukan</h3>
            <p style="color: #bbb; margin-bottom: 20px;">
                @if(request('search'))
                    Coba ubah pencarian Anda atau <a href="{{ route('admin.classes.index') }}" style="color: var(--primary); text-decoration: none;">reset filter</a>
                @else
                    Mulai dengan <a href="{{ route('admin.classes.create') }}" style="color: var(--primary); text-decoration: none;">membuat kelas baru</a>
                @endif
            </p>
        </div>
    @else
        <!-- Classes Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
            @foreach($classes as $class)
                <div class="card" style="display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" 
                     onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.2)'" 
                     onmouseout="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'">
                    <!-- Header -->
                    <div style="padding: 20px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 8px 8px 0 0;">
                        <h3 style="margin: 0; font-size: 18px;">
                            <i class="fas fa-chalkboard"></i> {{ $class->name }}
                        </h3>
                        <small style="color: rgba(255,255,255,0.8); margin-top: 5px; display: block;">
                            {{ $class->day_of_week }} • {{ date('H:i', strtotime($class->start_time)) }} - {{ date('H:i', strtotime($class->end_time)) }}
                        </small>
                    </div>

                    <!-- Body -->
                    <div style="flex: 1; padding: 20px;">
                        <!-- Stats -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                            <div style="text-align: center; padding: 15px; background: #f5f5f5; border-radius: 8px;">
                                <div style="font-size: 24px; font-weight: 700; color: var(--primary);">
                                    {{ $class->subjects()->count() }}
                                </div>
                                <small style="color: #999; font-size: 12px; display: block; margin-top: 5px;">
                                    <i class="fas fa-book"></i> Mata Pelajaran
                                </small>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #f5f5f5; border-radius: 8px;">
                                <div style="font-size: 24px; font-weight: 700; color: var(--secondary);">
                                    {{ $class->students()->count() }}
                                </div>
                                <small style="color: #999; font-size: 12px; display: block; margin-top: 5px;">
                                    <i class="fas fa-users"></i> Siswa
                                </small>
                            </div>
                        </div>

                        <!-- Description -->
                        <p style="color: #666; font-size: 13px; line-height: 1.5; margin: 0;">
                            {{ Str::limit($class->description, 60) ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>

                    <!-- Footer -->
                    <div style="padding: 15px 20px; border-top: 1px solid var(--border); display: flex; gap: 10px;">
                        <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-primary" style="flex: 1; text-align: center; text-decoration: none; font-size: 13px;">
                            <i class="fas fa-eye"></i> Kelola
                        </a>
                        <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; font-size: 13px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($classes->hasPages())
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 40px;">
                <!-- Previous Button -->
                @if($classes->onFirstPage())
                    <button disabled class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed;">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                @else
                    <a href="{{ $classes->previousPageUrl() }}" class="btn btn-secondary" style="text-decoration: none;">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                @endif

                <!-- Page Info -->
                <div style="padding: 8px 16px; background: #f5f5f5; border-radius: 8px; font-size: 13px; color: #666;">
                    Halaman {{ $classes->currentPage() }} dari {{ $classes->lastPage() }}
                </div>

                <!-- Next Button -->
                @if($classes->hasMorePages())
                    <a href="{{ $classes->nextPageUrl() }}" class="btn btn-primary" style="text-decoration: none;">
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

    <style>
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 20px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
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
            background: var(--primary-dark, #5a5fd8);
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

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }
    </style>
@endsection
