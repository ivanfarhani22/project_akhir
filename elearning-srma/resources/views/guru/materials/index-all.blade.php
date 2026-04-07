@extends('layouts.guru')

@section('title', 'Materi Pembelajaran')
@section('icon', 'fas fa-book')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="page-title">
                <i class="fas fa-book"></i>
                Materi Pembelajaran
            </h1>
            <p class="page-description">Kelola semua materi pembelajaran Anda</p>
        </div>
        <a href="{{ route('guru.materials.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Upload Materi
        </a>
    </div>

    <!-- Filter Kelas -->
    @if($classes->count() > 0)
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-body">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('guru.materials.index') }}" class="btn btn-secondary btn-sm" style="background: var(--primary); color: white;">
                        <i class="fas fa-list"></i> Semua Materi
                    </a>
                    @foreach($classes as $class)
                        <a href="{{ route('guru.materials.index', ['class_id' => $class->id]) }}" class="btn btn-secondary btn-sm">
                            {{ $class->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Materi</div>
            <span style="background: #f0f0f0; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                Total: {{ $materials->count() }}
            </span>
        </div>
        <div class="card-body">
            @if($materials->isEmpty())
                <div style="text-align: center; padding: 60px 20px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                    <p style="font-size: 16px;">Belum ada materi</p>
                    <a href="{{ route('guru.materials.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Upload Materi Pertama
                    </a>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    @foreach($materials as $material)
                        <div class="card" style="position: relative;">
                            <div class="card-header">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div>
                                        <h3 class="card-title">{{ Str::limit($material->title, 30) }}</h3>
                                        <p style="font-size: 12px; color: #999; margin-top: 5px;">
                                            <i class="fas fa-folder"></i> {{ $material->eClass->name }}
                                        </p>
                                    </div>
                                    <span class="badge" style="background-color: #e7f3f0; color: #00897b; font-size: 11px;">v{{ $material->version }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p style="color: #666; font-size: 13px; margin-bottom: 15px;">
                                    {{ Str::limit($material->description, 100) }}
                                </p>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid var(--border);">
                                    <span style="font-size: 11px; color: #999;">
                                        <i class="fas fa-file-{{ $material->file_type }}"></i> {{ strtoupper($material->file_type) }}
                                    </span>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('guru.materials.edit', $material) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('guru.materials.destroy', $material) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
