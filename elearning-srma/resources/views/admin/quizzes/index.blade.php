@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1"><i class="fas fa-question-circle mr-1"></i> Admin / Quiz</p>
            <h1 class="text-2xl font-extrabold text-slate-900"><i class="fas fa-question-circle text-[#C41E3A] mr-2"></i>Quiz</h1>
            <p class="text-sm text-slate-500 mt-1">Buat dan kelola quiz untuk semua kelas.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
            <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
            <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- BUAT QUIZ BARU --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="h-1 bg-gradient-to-r from-[#C41E3A] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="font-bold text-slate-900">Buat Quiz Baru</h2>
            <p class="text-xs text-slate-500 mt-0.5">Isi informasi dasar, lalu kelola soal di halaman berikutnya.</p>
        </div>
        <div class="p-6">
            {{-- Admin menggunakan flow yang sama (buat assignment tipe quiz) --}}
            <form method="POST" action="{{ route('admin.quizzes.create-assignment') }}" class="space-y-4 max-w-xl">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mapel <span class="text-red-500">*</span></label>
                    <select name="class_subject_id" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition bg-white">
                        <option value="" disabled selected>Pilih mapel...</option>
                        @foreach(($classSubjects ?? collect()) as $cs)
                            <option value="{{ $cs->id }}">{{ $cs->eClass?->name }} — {{ $cs->subject?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Quiz <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Quiz Bab 1 — Fotosintesis"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <textarea name="description" rows="2" placeholder="Instruksi singkat untuk siswa..."
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition resize-none">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deadline <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                </div>
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 bg-[#C41E3A] hover:bg-[#9B1630] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                    <i class="fas fa-plus text-xs"></i> Buat & Kelola Soal
                </button>
            </form>
        </div>
    </div>

    {{-- DAFTAR QUIZ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="font-bold text-slate-900">Daftar Quiz</h2>
            <span class="bg-slate-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $quizzes->total() }}</span>
        </div>

        @if($quizzes->count() === 0)
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-question-circle text-3xl text-slate-300"></i>
                </div>
                <p class="text-slate-500 text-sm">Belum ada quiz. Buat dari formulir di atas.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Judul</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Kelas / Mapel</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Soal</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Diupdate</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($quizzes as $quiz)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $quiz->assignment?->title ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <p class="text-slate-800 font-medium">{{ $quiz->assignment?->eClass?->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $quiz->assignment?->classSubject?->subject?->name ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-sm font-bold text-slate-700">{{ $quiz->questions->count() }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($quiz->status === 'published')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">{{ optional($quiz->updated_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($quiz->assignment)
                                        <a href="{{ route('admin.quizzes.manage', $quiz->assignment) }}"
                                           class="inline-flex items-center gap-1.5 bg-[#C41E3A] hover:bg-[#9B1630] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                            <i class="fas fa-pen text-[10px]"></i> Kelola
                                        </a>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">{{ $quizzes->links() }}</div>
        @endif
    </div>
</div>
@endsection
