@extends('layouts.siswa')
@section('title', 'Mata Pelajaran')
@section('icon', 'fas fa-book')

@section('content')
<div class="flex items-start justify-between gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Siswa / Mata Pelajaran</p>
        <h1 class="text-2xl font-extrabold text-gray-900">Mata Pelajaran</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar mata pelajaran yang Anda ikuti.</p>
    </div>
</div>

@if(($classSubjects ?? collect())->isEmpty())
    <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
        <p class="text-gray-500 text-sm">Belum ada mata pelajaran.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($classSubjects as $cs)
            <a href="{{ route('siswa.subjects.show', $cs) }}"
               class="group block bg-white rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-md transition overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-emerald-400 to-teal-400"></div>
                <div class="p-5">
                    <h3 class="font-extrabold text-gray-900 truncate">{{ $cs->subject->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $cs->eClass->name }}</p>
                    <p class="text-xs text-gray-400 mt-2"><i class="fas fa-chalkboard-teacher mr-1"></i>{{ $cs->teacher->name }}</p>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection