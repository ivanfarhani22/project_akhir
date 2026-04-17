@extends('layouts.guru')

@section('title', 'Detail Tugas')
@section('icon', 'fas fa-eye')

@section('content')
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <p class="text-gray-600 text-sm mb-2">Manajemen Tugas</p>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-eye text-blue-500"></i>
                Detail Tugas
            </h1>
            <p class="text-gray-600 text-sm">
                <span class="font-semibold">{{ $assignment->title }}</span>
                <span class="text-gray-400">•</span>
                Kelas: <span class="font-semibold">{{ $class->name }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('guru.assignments.edit', $assignment) }}" class="bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-4 rounded-lg text-sm transition inline-flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ url()->previous() ?? route('guru.assignments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-4 rounded-lg text-sm transition inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @php
        $submittedCount = $assignment->submissions->whereNotNull('submitted_at')->count();
        $totalStudents = $class->students->count();
        $percentage = $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100) : 0;
        $downloadUrl = null;
        if ($assignment->file_path) {
            $downloadUrl = str_starts_with($assignment->file_path, 'storage/')
                ? asset($assignment->file_path)
                : asset('storage/' . ltrim($assignment->file_path, '/'));
        }
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- MAIN (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assignment Detail -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Informasi Tugas
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-600 text-sm font-semibold">Deadline</span>
                        <span class="text-gray-900 text-sm font-bold">{{ $assignment->deadline?->format('d M Y H:i') ?? '-' }}</span>
                    </div>

                    @if($downloadUrl)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-gray-600 text-xs font-semibold mb-2 uppercase">Lampiran Soal</p>
                            <a href="{{ $downloadUrl }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-gray-600 text-xs font-semibold mb-2 uppercase">Deskripsi</p>
                        <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-line">{{ $assignment->description }}</div>
                    </div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-file-upload text-green-600"></i>
                        Pengumpulan Siswa
                    </h2>
                    <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $assignment->submissions->count() }} data
                    </span>
                </div>

                <div class="overflow-x-auto">
                    @if($assignment->submissions->isEmpty())
                        <div class="p-8 text-center text-gray-600">
                            Belum ada data submission.
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Siswa</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Status</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Waktu Submit</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">File</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($assignment->submissions as $submission)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 font-medium text-gray-900">{{ $submission->student?->name ?? '-' }}</td>
                                        <td class="px-6 py-3">
                                            @if($submission->submitted_at)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle"></i> Submitted
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 text-gray-700">
                                            {{ $submission->submitted_at?->format('d M Y H:i') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-3">
                                            @if($submission->file_path)
                                                <a href="{{ route('guru.submissions.download', $submission) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold" title="Download file">
                                                    <i class="fas fa-download"></i>
                                                    <span class="hidden sm:inline">Download</span>
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- SIDEBAR (1/3) -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-blue-500"></i>
                        Ringkasan
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-gray-600 text-xs font-semibold uppercase">Submission</p>
                        <p class="text-gray-900 font-bold text-lg">{{ $submittedCount }}/{{ $totalStudents }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs font-semibold uppercase">Progress</p>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-green-600" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-gray-600 text-xs mt-2">{{ $percentage }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
