@extends('layouts.siswa')

@section('title', 'Kelas Saya')
@section('icon', 'fas fa-chalkboard')

@section('content')
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-chalkboard"></i>
            Kelas Saya
        </h1>
        <p class="page-description">Daftar kelas yang Anda ikuti</p>
    </div>

    @if($classes->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @foreach($classes as $class)
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 600; color: var(--secondary); margin-bottom: 5px;">
                                    {{ $class->name }}
                                </h3>
                                <p style="color: #999; font-size: 14px;">{{ $class->subject->name }}</p>
                            </div>
                            <span style="background: var(--primary); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $class->students->count() }} Siswa
                            </span>
                        </div>

                        <p style="color: #666; font-size: 13px; margin-bottom: 5px;">
                            <strong>Guru:</strong> {{ $class->teacher->name }}
                        </p>
                        <p style="color: #999; font-size: 12px; margin-bottom: 15px;">
                            {{ $class->description }}
                        </p>

                        <div style="border-top: 1px solid var(--border); padding-top: 15px; display: flex; gap: 10px;">
                            <a href="#" class="btn btn-primary btn-sm" style="flex: 1; justify-content: center;">
                                <i class="fas fa-book"></i> Materi
                            </a>
                            <a href="#" class="btn btn-secondary btn-sm" style="flex: 1; justify-content: center;">
                                <i class="fas fa-tasks"></i> Tugas
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 64px; color: #ddd; margin-bottom: 20px; display: block;"></i>
                <p style="color: #999; font-size: 16px;">Anda belum terdaftar di kelas apapun</p>
            </div>
        </div>
    @endif
@endsection
