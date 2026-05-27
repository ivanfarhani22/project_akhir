@extends('layouts.guru')
@section('title', 'Quiz')
@section('icon', 'fas fa-question-circle')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-question-circle mr-1"></i> Guru / Quiz</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-question-circle text-[#A41E35] mr-2"></i>Quiz</h1>
        <p class="text-sm text-gray-500 mt-1">Buat dan kelola quiz untuk mapel yang Anda ampu.</p>
    </div>
</div>

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

{{-- BUAT QUIZ BARU --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Buat Quiz Baru</h2>
        <p class="text-xs text-gray-400 mt-0.5">Isi informasi dasar, lalu kelola soal di halaman berikutnya.</p>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('guru.quizzes.create-assignment') }}" class="space-y-4 max-w-xl">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mapel <span class="text-red-500">*</span></label>
                <select name="class_subject_id" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                    <option value="" disabled selected>Pilih mapel...</option>
                    @foreach($classSubjects as $cs)
                        <option value="{{ $cs->id }}">{{ $cs->eClass?->name }} — {{ $cs->subject?->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Quiz <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Quiz Bab 1 — Fotosintesis"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="description" rows="2" placeholder="Instruksi singkat untuk siswa..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deadline <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
            </div>
            <button type="submit"
                class="inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                <i class="fas fa-plus text-xs"></i> Buat & Kelola Soal
            </button>
        </form>
    </div>
</div>

{{-- DAFTAR QUIZ --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Daftar Quiz</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $quizzes->total() }}</span>
    </div>

    @if($quizzes->count() === 0)
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-question-circle text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Belum ada quiz. Buat dari formulir di atas.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas / Mapel</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Soal</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Diupdate</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($quizzes as $quiz)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 font-semibold text-gray-900">{{ $quiz->assignment?->title ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                <p class="text-gray-800 font-medium">{{ $quiz->assignment?->eClass?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $quiz->assignment?->classSubject?->subject?->name ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-bold text-gray-700">{{ $quiz->questions->count() }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($quiz->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">{{ optional($quiz->updated_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @if($quiz->assignment)
                                    <a href="{{ route('guru.quizzes.manage', $quiz->assignment) }}"
                                       class="inline-flex items-center gap-1.5 bg-[#A41E35] hover:bg-[#7D1627] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                        <i class="fas fa-pen text-[10px]"></i> Kelola
                                    </a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $quizzes->links() }}</div>
    @endif
</div>
@endsection