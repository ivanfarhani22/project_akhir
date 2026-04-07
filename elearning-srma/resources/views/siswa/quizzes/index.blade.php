@extends('layouts.siswa')

@section('title', 'Quiz / Ujian')
@section('icon', 'fas fa-question-circle')

@section('content')
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-question-circle"></i>
            Quiz / Ujian
        </h1>
        <p class="page-description">Ikuti quiz dan ujian untuk menguji pemahaman Anda</p>
    </div>

    @php
        $myClasses = auth()->user()->classes;
    @endphp

    <div class="card">
        <div class="card-body" style="text-align: center; padding: 80px 20px;">
            <i class="fas fa-wrench" style="font-size: 64px; color: #ddd; margin-bottom: 20px; display: block;"></i>
            <h2 style="color: var(--secondary); margin-bottom: 10px;">Fitur Segera Hadir</h2>
            <p style="color: #999; font-size: 16px; margin-bottom: 20px;">Fitur Quiz dan Ujian sedang dalam pengembangan</p>
            <p style="color: #666; font-size: 14px;">Kami sedang mempersiapkan platform quiz yang interaktif dan menyenangkan untuk Anda</p>
        </div>
    </div>

    <!-- INFO CARD -->
    <div class="card" style="margin-top: 20px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9);">
        <div class="card-body">
            <h3 style="color: #27ae60; margin-bottom: 10px;">
                <i class="fas fa-info-circle"></i>
                Informasi
            </h3>
            <p style="color: #2d5016; font-size: 14px;">
                Fitur Quiz dan Ujian akan memungkinkan Anda untuk menguji pemahaman materi pembelajaran melalui pertanyaan interaktif dengan umpan balik otomatis.
            </p>
        </div>
    </div>
@endsection
