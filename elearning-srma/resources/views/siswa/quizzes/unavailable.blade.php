@extends('layouts.siswa')
@section('title', 'Quiz')
@section('icon', 'fas fa-question-circle')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-question-circle mr-1"></i> Siswa / Quiz</p>
        <h1 class="text-2xl font-extrabold text-gray-900">Quiz Tidak Tersedia</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $assignment->title }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900">{{ $title }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $message }}</p>

                    @if(!empty($meta))
                        <div class="mt-4 text-sm text-gray-700 space-y-1">
                            @foreach($meta as $k => $v)
                                <div class="flex justify-between gap-6">
                                    <span class="text-gray-500">{{ $k }}</span>
                                    <span class="font-semibold">{{ $v }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('siswa.assignments.show', $assignment) }}" class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50">Kembali ke Tugas</a>
                        <a href="{{ route('siswa.grades.index') }}" class="px-4 py-2 rounded-xl bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold">Lihat Nilai</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
