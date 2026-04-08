@extends('layouts.guru')

@section('title', 'Nilai Siswa')
@section('icon', 'fas fa-star')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-star text-yellow-500"></i>
            Nilai Siswa
        </h1>
        <p class="text-gray-600 text-sm">Kelola nilai dan penilaian siswa</p>
    </div>

    <!-- ASSIGNMENT FILTER -->
    @if($assignments->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-8">
            <p class="text-gray-700 text-sm mb-4 font-medium">Filter berdasarkan tugas:</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('guru.grades.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-1.5 px-4 rounded text-sm transition inline-flex items-center gap-2">
                    <i class="fas fa-list"></i> Semua Penilaian
                </a>
                @foreach($assignments as $assignment)
                    <a href="{{ route('guru.grades.index', ['assignment_id' => $assignment->id]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-1.5 px-4 rounded text-sm transition inline-flex items-center gap-1">
                        {{ Str::limit($assignment->title, 30) }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- GRADES TABLE -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 text-lg">Daftar Penilaian</h2>
            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                Total: {{ $submissions->count() }}
            </span>
        </div>
        
        <div class="overflow-x-auto">
            @if($submissions->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
                    <p class="text-gray-600 text-base">Belum ada pengumpulan</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left font-semibold text-gray-900">Siswa</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-900">Tugas</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-900">Nilai</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-900">Tanggal Dinilai</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                            @php
                                $grade = $submission->grade;
                                $gradeColor = !$grade ? 'bg-gray-100 text-gray-800' : ($grade->score >= 80 ? 'bg-green-100 text-green-800' : ($grade->score >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'));
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $submission->student->name }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ Str::limit($submission->assignment->title, 30) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($submission->submitted_at)
                                        <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Terkumpul</span>
                                    @else
                                        <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Belum Dikumpul</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($grade)
                                        <span class="inline-block {{ $gradeColor }} text-xs font-semibold px-3 py-1 rounded">
                                            {{ $grade->score }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 text-xs">
                                    {{ $grade && $grade->graded_at ? $grade->graded_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('guru.grades.edit', $submission) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-1.5 px-3 rounded text-xs transition inline-flex items-center gap-1">
                                        <i class="fas fa-edit"></i> Nilai
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
