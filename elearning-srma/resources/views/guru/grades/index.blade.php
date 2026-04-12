@extends('layouts.guru')

@section('title', 'Penilaian')
@section('icon', 'fas fa-star')

@section('content')
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <p class="text-gray-600 text-sm mb-2">Kelola Nilai</p>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <i class="fas fa-star text-yellow-500"></i>
                Penilaian
            </h1>
            <p class="text-gray-600 text-sm">Tugas: <strong>{{ $assignment->title }}</strong></p>
        </div>
        <a href="{{ route('guru.grades.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded-lg text-sm transition inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Semua Penilaian
        </a>
    </div>

    <!-- SUBMISSIONS TABLE -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 text-lg">Daftar Pengumpulan</h2>
            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                Total: {{ $submissions->count() }}
            </span>
        </div>

        @if($submissions->isEmpty())
            <div class="text-center py-12 px-6">
                <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-gray-600 text-base">Belum ada pengumpulan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-900">Siswa</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-900">Waktu Kirim</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-900">Nilai</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                            @php
                                $grade = $submission->grade;
                                $isGraded = !is_null($grade);
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $submission->student->name }}</div>
                                    <div class="text-xs text-gray-500">NIS: {{ $submission->student->nis ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ optional($submission->submitted_at)->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($isGraded)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            {{ $grade->score }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            Belum dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        <a href="{{ route('guru.grades.edit', $submission) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg text-xs transition inline-flex items-center gap-2">
                                            <i class="fas fa-pen"></i> {{ $isGraded ? 'Ubah Nilai' : 'Nilai' }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
