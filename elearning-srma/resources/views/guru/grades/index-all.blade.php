@extends('layouts.guru')
@section('title', 'Penilaian')
@section('icon', 'fas fa-star')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-star mr-1"></i> Guru / Penilaian</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-star text-yellow-400 mr-2"></i>Penilaian</h1>
    </div>
    <a href="{{ route('guru.rekap-nilai.index') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition whitespace-nowrap">
        <i class="fas fa-table text-xs"></i> Rekap Nilai
    </a>
</div>

@if($assignments->count() > 0)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900 text-sm">Filter Tugas</h2>
        </div>
        <div class="p-4 flex flex-wrap gap-2">
            <a href="{{ route('guru.grades.index') }}"
               class="inline-flex items-center gap-1.5 bg-[#A41E35] text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                <i class="fas fa-list text-[10px]"></i> Semua
            </a>
            @foreach($assignments as $assignment)
                <a href="{{ route('guru.grades.index', ['assignment_id' => $assignment->id]) }}"
                   class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg transition">
                    {{ Str::limit($assignment->title, 30) }}
                </a>
            @endforeach
        </div>
    </div>
@endif

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
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
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Dinilai</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($submissions as $submission)
                        @php
                            $grade = $submission->grade;
                            $scoreColor = !$grade ? 'bg-gray-100 text-gray-500 border-gray-200' : ($grade->score >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($grade->score >= 70 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200'));
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $submission->student->name }}</td>
                            <td class="px-5 py-3.5 text-gray-600">{{ Str::limit($submission->assignment->title, 30) }}</td>
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
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $scoreColor }}">
                                        {{ $grade->score }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-400">
                                {{ $grade && $grade->graded_at ? $grade->graded_at->format('d M Y') : '—' }}
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
@endsection