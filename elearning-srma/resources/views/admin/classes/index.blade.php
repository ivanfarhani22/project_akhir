@extends('layouts.admin')

@section('title', 'Kelola Kelas')
@section('icon', 'chalkboard')

@section('content')
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="page-title">
                <i class="fas fa-chalkboard"></i>
                Kelola Kelas
            </h1>
            <p class="page-description">Kelola kelas, mata pelajaran, guru, dan siswa</p>
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

    @if($classes->isEmpty())
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px 20px;">
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
        </div>
    @else
        <!-- Classes Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px; margin-bottom: 30px;">
            @foreach($classes as $class)
                <div class="card" style="display: flex; flex-direction: column;">
                    <!-- Card Header with Schedule Info -->
                    <div class="card-header" style="background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%); color: white; position: relative;">
                        <div class="card-title" style="color: white; margin-bottom: 8px;">{{ $class->name }}</div>
                        @if ($class->day_of_week)
                            <div style="font-size: 12px; opacity: 0.9;">
                                📅 {{ ucfirst($class->day_of_week) }}
                                @if ($class->start_time && $class->end_time)
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $class->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $class->end_time)->format('H:i') }}
                                @endif
                                @if ($class->room)
                                    • {{ $class->room }}
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="card-body" style="flex-grow: 1; display: flex; flex-direction: column;">
                        <!-- Subjects Section -->
                        <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <div style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                    📖 Mata Pelajaran
                                </div>
                                <span style="background: var(--primary); color: white; border-radius: 20px; padding: 4px 10px; font-size: 12px; font-weight: 600;">
                                    {{ $class->classSubjects->count() }}
                                </span>
                            </div>

                            @if ($class->classSubjects->isNotEmpty())
                                <ul style="margin: 0; padding: 0; list-style: none;">
                                    @foreach ($class->classSubjects->take(2) as $cs)
                                        <li style="padding: 6px 0; font-size: 13px; color: #666; display: flex; justify-content: space-between;">
                                            <span>• {{ $cs->subject->name }}</span>
                                            <span style="color: #999; font-size: 12px;">{{ substr($cs->teacher->name, 0, 15) }}</span>
                                        </li>
                                    @endforeach
                                    @if ($class->classSubjects->count() > 2)
                                        <li style="padding: 6px 0; color: #999; font-size: 12px; font-style: italic;">
                                            +{{ $class->classSubjects->count() - 2 }} lainnya
                                        </li>
                                    @endif
                                </ul>
                            @else
                                <p style="margin: 0; font-size: 13px; color: #999;">
                                    ⚠️ Belum ada mata pelajaran
                                </p>
                            @endif
                        </div>

                        <!-- Students Section -->
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <div style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                    👥 Siswa
                                </div>
                                <span style="background: var(--success); color: white; border-radius: 20px; padding: 4px 10px; font-size: 12px; font-weight: 600;">
                                    {{ $class->students->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div style="display: flex; gap: 10px; margin-top: auto;">
                            <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; padding: 10px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; padding: 10px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
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
@endsection

