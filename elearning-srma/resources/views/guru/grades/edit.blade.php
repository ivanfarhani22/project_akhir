@extends('layouts.guru')
@section('title', 'Nilai Tugas')
@section('icon', 'fas fa-star')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-star mr-1"></i> Guru / Penilaian / Beri Nilai</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-star text-yellow-400 mr-2"></i>Beri Nilai Tugas</h1>
    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
        <i class="fas fa-tasks"></i> {{ $assignment->title }}
        <span class="mx-1 text-gray-300">•</span>
        <i class="fas fa-user"></i> {{ $submission->student->name }}
    </span>
</div>

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-900"><i class="fas fa-file-check mr-2 text-gray-400"></i>Info Submission</h3>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1.5">Status</p>
                @if($submission->submitted_at)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="fas fa-check-circle text-[10px]"></i> Dikumpulkan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                        <i class="fas fa-clock text-[10px]"></i> Belum Dikumpulkan
                    </span>
                @endif
            </div>
            @if($submission->submitted_at)
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Waktu Pengumpulan</p>
                    <p class="text-sm font-bold text-gray-900">{{ $submission->submitted_at->format('d M Y H:i') }}</p>
                </div>
                @if($submission->submitted_at > $assignment->deadline)
                    <div class="flex items-start gap-2 bg-red-50 border border-red-200 px-3 py-2.5 rounded-xl">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 text-xs flex-shrink-0"></i>
                        <p class="text-xs text-red-700 font-semibold">Terlambat! {{ $submission->submitted_at->diffForHumans($assignment->deadline) }}</p>
                    </div>
                @endif
            @endif
            @if($submission->file_path)
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1.5">File</p>
                    <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                        <i class="fas fa-download text-[10px]"></i> Download File
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-900"><i class="fas fa-info-circle mr-2 text-gray-400"></i>Info Tugas</h3>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Batas Waktu</p>
                <p class="text-sm font-bold text-gray-900">{{ $assignment->deadline->format('d M Y H:i') }}</p>
            </div>
            @if($assignment->description)
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Deskripsi</p>
                    <p class="text-sm text-gray-700 line-clamp-3 leading-relaxed">{{ $assignment->description }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-yellow-400 to-amber-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900"><i class="fas fa-pen-to-square mr-2 text-yellow-500"></i>Form Penilaian</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('guru.grades.update', $submission) }}">
                @csrf @method('PUT')

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nilai (0–100) <span class="text-red-500">*</span></label>
                    <input type="number" name="score" min="0" max="100" step="1" placeholder="Masukkan nilai 0–100"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition"
                        value="{{ old('score', $submission->grade->score ?? '') }}" required>
                    @error('score')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Komentar / Feedback</label>
                    <textarea name="feedback" rows="4" placeholder="Berikan feedback atau komentar untuk siswa..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition resize-none">{{ old('feedback', $submission->grade->feedback ?? '') }}</textarea>
                    @error('feedback')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ url()->previous() ?? route('guru.grades.index') }}"
                       class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection