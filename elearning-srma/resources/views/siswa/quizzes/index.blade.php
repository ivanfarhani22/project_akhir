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

    <div class="p-6">
        @if($quizzes->count() === 0)
            <div class="flex flex-col items-center justify-center py-10 px-6 text-center">
                <div class="w-24 h-24 bg-purple-50 border-2 border-dashed border-purple-200 rounded-2xl flex items-center justify-center mb-5">
                    <i class="fas fa-info-circle text-4xl text-purple-300"></i>
                </div>
                <h2 class="text-xl font-extrabold text-gray-900 mb-2">Belum ada quiz</h2>
                <p class="text-gray-500 text-sm max-w-sm">Quiz akan muncul di sini jika guru/admin sudah membuat quiz untuk mapel Anda.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-3 pr-4">Judul</th>
                            <th class="py-3 pr-4">Kelas</th>
                            <th class="py-3 pr-4">Mapel</th>
                            <th class="py-3 pr-4">Guru</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quizzes as $quiz)
                            <tr class="border-b last:border-b-0">
                                <td class="py-3 pr-4 font-medium text-gray-900">{{ $quiz->assignment?->title ?? '—' }}</td>
                                <td class="py-3 pr-4">{{ $quiz->assignment?->eClass?->name ?? '—' }}</td>
                                <td class="py-3 pr-4">{{ $quiz->assignment?->classSubject?->subject?->name ?? '—' }}</td>
                                <td class="py-3 pr-4">{{ $quiz->assignment?->classSubject?->teacher?->name ?? '—' }}</td>
                                <td class="py-3 pr-4">
                                    @if($quiz->status === 'published')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">published</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">draft</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">
                                    @if($quiz->assignment)
                                        <a href="{{ route('siswa.quizzes.show', $quiz->assignment) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-purple-600 text-white text-xs font-semibold hover:bg-purple-700">
                                            Buka
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $quizzes->links() }}
            </div>
        @endif
    </div>
</div>

<div class="flex items-start gap-3 bg-emerald-50 border border-emerald-100 rounded-2xl px-5 py-4">
    <i class="fas fa-info-circle text-emerald-500 mt-0.5 flex-shrink-0"></i>
    <p class="text-sm text-emerald-800">Jika quiz berstatus <b>draft</b>, Anda akan melihat halaman info saat membukanya. Quiz harus <b>published</b> agar bisa dikerjakan.</p>
</div>
@endsection