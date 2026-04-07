@extends('layouts.guru')

@section('title', 'Upload Materi Baru')
@section('icon', 'fas fa-book')

@section('content')
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-book"></i>
            Upload Materi Baru
        </h1>
        <p class="page-description">Pilih kelas untuk upload materi</p>
    </div>

    @if($classes->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @foreach($classes as $class)
                <div class="card">
                    <div class="card-body">
                        <h3 style="font-size: 18px; font-weight: 600; color: var(--secondary); margin-bottom: 10px;">
                            {{ $class->name }}
                        </h3>
                        <p style="color: #666; font-size: 14px; margin-bottom: 5px;">
                            <strong>Mata Pelajaran:</strong> {{ $class->subject->name }}
                        </p>
                        <p style="color: #999; font-size: 13px; margin-bottom: 20px;">
                            {{ $class->students->count() }} siswa
                        </p>

                        <a href="{{ route('guru.materials.create', ['class_id' => $class->id]) }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-plus"></i> Upload Materi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 64px; color: #ddd; margin-bottom: 20px; display: block;"></i>
                <p style="color: #999; font-size: 16px;">Anda belum mengajar kelas apapun</p>
            </div>
        </div>
    @endif
@endsection
