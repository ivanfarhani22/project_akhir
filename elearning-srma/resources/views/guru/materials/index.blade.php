@extends('layouts.guru')

@section('title', 'Materi Pembelajaran')
@section('icon', 'fas fa-book')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Kelola Materi</p>
            <h1 class="page-title">
                <i class="fas fa-book"></i>
                Materi Pembelajaran
            </h1>
            <p class="page-description">Kelas: <strong>{{ $class->name }}</strong></p>
        </div>
        <a href="{{ route('guru.materials.create', ['class_id' => $class->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Upload Materi
        </a>
    </div>

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
                    <a href="{{ route('guru.materials.create', ['class_id' => $class->id]) }}" class="btn btn-primary" style="margin-top: 15px;">
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
                                            <i class="fas fa-file"></i> {{ strtoupper($material->file_type) }}
                                        </p>
                                    </div>
                                    <span class="badge" style="background-color: #e7f3f0; color: #00897b; font-size: 11px;">v{{ $material->version }}</span>
                                </div>
                            </div>
                            <div class="card-body" style="padding: 15px 20px;">
                                @if($material->description)
                                    <p style="font-size: 13px; color: #666; margin-bottom: 10px; line-height: 1.5;">
                                        {{ Str::limit($material->description, 80) }}
                                    </p>
                                @endif

                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-top: 1px solid var(--border); margin-top: 10px; padding-top: 10px;">
                                    <span style="font-size: 12px; color: #999;">
                                        <i class="fas fa-calendar"></i> {{ $material->created_at->format('d M Y') }}
                                    </span>
                                    <span style="font-size: 12px; color: #999;">
                                        <i class="fas fa-eye"></i> Dibagikan
                                    </span>
                                </div>

                                <div style="display: flex; gap: 8px; margin-top: 15px;">
                                    <a href="{{ route('guru.materials.edit', $material) }}" class="btn btn-sm" style="flex: 1; background: #0066cc; color: white; text-decoration: none; text-align: center;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('guru.materials.destroy', $material) }}" style="flex: 1;" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm" style="width: 100%; background: #dc3545; color: white; border: none; cursor: pointer;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
