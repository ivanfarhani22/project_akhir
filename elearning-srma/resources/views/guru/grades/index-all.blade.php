@extends('layouts.guru')
@section('title', 'Penilaian')
@section('icon', 'fas fa-star')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-star mr-1"></i> Guru / Penilaian</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-star text-yellow-400 mr-2"></i>Penilaian</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor dan kelola nilai pengumpulan tugas siswa.</p>
    </div>
    <a href="{{ route('guru.rekap-nilai.index') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition whitespace-nowrap">
        <i class="fas fa-table text-xs"></i> Rekap Nilai
    </a>
</div>

{{-- FILTER BERTINGKAT --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="h-1 bg-gradient-to-r from-yellow-400 to-amber-400"></div>
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900 text-sm">Filter Penilaian</h2>
    </div>
    <div class="p-4">
        <form method="GET" action="{{ route('guru.grades.index') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kelas & Mapel</label>
                <select name="class_subject_id" id="filter-cs" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition bg-white">
                    <option value="">Semua Kelas & Mapel</option>
                    @foreach($classSubjects as $cs)
                        <option value="{{ $cs->id }}" @selected(request('class_subject_id') == $cs->id)>
                            {{ $cs->eClass?->name }} — {{ $cs->subject?->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tugas</label>
                <select name="assignment_id" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition bg-white {{ !request('class_subject_id') ? 'opacity-50' : '' }}"
                    {{ !request('class_subject_id') ? 'disabled' : '' }}>
                    <option value="">Semua Tugas</option>
                    @foreach($assignments as $assignment)
                        <option value="{{ $assignment->id }}" @selected(request('assignment_id') == $assignment->id)>
                            {{ Str::limit($assignment->title, 40) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition">
                    <i class="fas fa-filter text-xs"></i> Filter
                </button>
                @if(request('class_subject_id') || request('assignment_id'))
                    <a href="{{ route('guru.grades.index') }}"
                       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition">
                        <i class="fas fa-times text-xs"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Breadcrumb filter aktif --}}
        @if(request('class_subject_id') || request('assignment_id'))
            <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-gray-400">Menampilkan:</span>
                @if(request('class_subject_id'))
                    @php $activeCs = $classSubjects->firstWhere('id', request('class_subject_id')); @endphp
                    @if($activeCs)
                        <span class="inline-flex items-center gap-1 bg-yellow-50 border border-yellow-200 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <i class="fas fa-chalkboard text-[10px]"></i>
                            {{ $activeCs->eClass?->name }} — {{ $activeCs->subject?->name }}
                        </span>
                    @endif
                @endif
                @if(request('assignment_id'))
                    @php $activeAs = $assignments->firstWhere('id', request('assignment_id')); @endphp
                    @if($activeAs)
                        <span class="inline-flex items-center gap-1 bg-blue-50 border border-blue-200 text-blue-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <i class="fas fa-tasks text-[10px]"></i>
                            {{ Str::limit($activeAs->title, 35) }}
                        </span>
                    @endif
                @endif
            </div>
        @endif
    </div>
</div>

{{-- RINGKASAN CEPAT --}}
@if($submissions->count() > 0)
    @php
        $graded   = $submissions->filter(fn($s) => $s->grade)->count();
        $ungraded = $submissions->count() - $graded;
        $avgScore = $submissions->filter(fn($s) => $s->grade)->avg(fn($s) => $s->grade->score);
    @endphp
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-4 px-3">
            <p class="text-2xl font-extrabold text-gray-900">{{ $submissions->count() }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-4 px-3">
            <p class="text-2xl font-extrabold text-emerald-600">{{ $graded }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Sudah Dinilai</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-4 px-3">
            <p class="text-2xl font-extrabold text-amber-500">{{ $ungraded }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Belum Dinilai</p>
        </div>
    </div>
@endif

{{-- TABEL DESKTOP --}}
<div class="hidden md:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Daftar Penilaian</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $submissions->count() }}</span>
    </div>

    @if($submissions->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-star text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Belum ada pengumpulan.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas / Mapel</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($submissions as $submission)
                        @php
                            $grade = $submission->grade;
                            $sc = !$grade ? 'bg-gray-100 text-gray-500 border-gray-200'
                                : ($grade->score >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : ($grade->score >= 70 ? 'bg-yellow-50 text-yellow-700 border-yellow-200'
                                : 'bg-red-50 text-red-600 border-red-200'));
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 font-semibold text-gray-900">{{ $submission->student->name }}</td>
                            <td class="px-5 py-3.5">
                                <p class="text-xs font-semibold text-gray-700">{{ $submission->assignment->eClass?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $submission->assignment->classSubject?->subject?->name ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">{{ Str::limit($submission->assignment->title, 32) }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @if($submission->submitted_at)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fas fa-check-circle text-[10px]"></i> Terkumpul
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-200">
                                        <i class="fas fa-times-circle text-[10px]"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($grade)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $sc }}">{{ $grade->score }}</span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-400">
                                {{ $grade?->graded_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="{{ route('guru.grades.edit', $submission) }}"
                                   class="inline-flex items-center gap-1.5 bg-yellow-50 hover:bg-yellow-500 text-yellow-600 hover:text-white border border-yellow-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                    <i class="fas fa-pen text-[10px]"></i> Nilai
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- CARD MOBILE --}}
<div class="md:hidden space-y-3">
    @forelse($submissions as $submission)
        @php
            $grade = $submission->grade;
            $sc = !$grade ? 'bg-gray-100 text-gray-500 border-gray-200'
                : ($grade->score >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                : ($grade->score >= 70 ? 'bg-yellow-50 text-yellow-700 border-yellow-200'
                : 'bg-red-50 text-red-600 border-red-200'));
        @endphp
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-start justify-between gap-3 p-4 border-b border-gray-100">
                <div class="min-w-0">
                    <p class="font-bold text-gray-900 text-sm truncate">{{ $submission->student->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $submission->assignment->eClass?->name }} • {{ $submission->assignment->classSubject?->subject?->name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ Str::limit($submission->assignment->title, 40) }}</p>
                </div>
                @if($grade)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-extrabold border flex-shrink-0 {{ $sc }}">
                        {{ $grade->score }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-200 flex-shrink-0 whitespace-nowrap">
                        Belum dinilai
                    </span>
                @endif
            </div>
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center gap-2">
                    @if($submission->submitted_at)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600">
                            <i class="fas fa-check-circle text-[10px]"></i> {{ $submission->submitted_at->format('d M Y') }}
                        </span>
                    @else
                        <span class="text-xs text-red-500 font-semibold">Belum dikumpul</span>
                    @endif
                </div>
                <a href="{{ route('guru.grades.edit', $submission) }}"
                   class="inline-flex items-center gap-1.5 bg-yellow-50 hover:bg-yellow-500 text-yellow-600 hover:text-white border border-yellow-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                    <i class="fas fa-pen text-[10px]"></i> {{ $grade ? 'Ubah' : 'Nilai' }}
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <i class="fas fa-star text-gray-200 text-4xl mb-3"></i>
                <p class="text-gray-400 text-sm">Belum ada pengumpulan.</p>
            </div>
        </div>
    @endforelse
</div>

@endsection