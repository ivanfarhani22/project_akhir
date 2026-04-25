@extends('layouts.siswa')
@section('title', 'Quiz / Ujian')
@section('icon', 'fas fa-question-circle')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-question-circle mr-1"></i> Siswa / Quiz</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-question-circle text-purple-500 mr-2"></i>Quiz / Ujian</h1>
    <p class="text-sm text-gray-500 mt-1">Ikuti quiz dan ujian untuk menguji pemahaman Anda</p>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="h-1 bg-gradient-to-r from-purple-500 to-indigo-400"></div>
    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
        <div class="w-24 h-24 bg-purple-50 border-2 border-dashed border-purple-200 rounded-2xl flex items-center justify-center mb-5">
            <i class="fas fa-wrench text-4xl text-purple-300"></i>
        </div>
        <h2 class="text-xl font-extrabold text-gray-900 mb-2">Fitur Segera Hadir</h2>
        <p class="text-gray-500 text-sm max-w-sm">Fitur Quiz dan Ujian sedang dalam pengembangan. Kami sedang mempersiapkan platform quiz yang interaktif untuk Anda.</p>
    </div>
</div>

<div class="flex items-start gap-3 bg-emerald-50 border border-emerald-100 rounded-2xl px-5 py-4">
    <i class="fas fa-info-circle text-emerald-500 mt-0.5 flex-shrink-0"></i>
    <p class="text-sm text-emerald-800">Fitur ini akan memungkinkan Anda menguji pemahaman materi melalui pertanyaan interaktif dengan umpan balik otomatis.</p>
</div>
@endsection