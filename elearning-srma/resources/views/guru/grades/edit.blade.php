@extends('layouts.guru')

@section('title', 'Nilai Tugas')
@section('icon', 'fas fa-star')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <p class="text-gray-600 text-sm mb-2">Penilaian</p>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-star text-yellow-500"></i>
            Beri Nilai Tugas
        </h1>
        <p class="text-gray-600 text-sm">
            Tugas: <strong>{{ $assignment->title }}</strong> • 
            Siswa: <strong>{{ $submission->student->name }}</strong>
        </p>
    </div>

    <!-- ERROR ALERT -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-900 font-semibold flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-circle"></i>
                Terjadi kesalahan:
            </p>
            <ul class="text-red-800 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- INFO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Submission Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                    <i class="fas fa-file-check text-blue-500"></i>
                    Informasi Submission
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Status -->
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-2">Status</p>
                    @if($submission->submitted_at)
                        <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded">
                            <i class="fas fa-check-circle mr-1"></i> Dikumpulkan
                        </span>
                    @else
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded">
                            <i class="fas fa-clock mr-1"></i> Belum Dikumpulkan
                        </span>
                    @endif
                </div>

                @if($submission->submitted_at)
                    <!-- Submission Time -->
                    <div>
                        <p class="text-xs text-gray-600 font-medium mb-1">Waktu Pengumpulan</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $submission->submitted_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    @if($submission->submitted_at > $assignment->deadline)
                        <div class="bg-red-50 border-l-4 border-red-500 p-3">
                            <p class="text-xs text-red-900">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Terlambat!</strong> Dikumpulkan {{ $submission->submitted_at->diffForHumans($assignment->deadline) }}
                            </p>
                        </div>
                    @endif
                @endif

                @if($submission->file_path)
                    <!-- File Download -->
                    <div>
                        <p class="text-xs text-gray-600 font-medium mb-2">File</p>
                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded text-xs transition">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Assignment Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Informasi Tugas
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Deadline -->
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-1">Batas Waktu</p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $assignment->deadline->format('d M Y H:i') }}
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-1">Deskripsi</p>
                    <p class="text-sm text-gray-900 line-clamp-3">
                        {{ $assignment->description ?: 'Tidak ada deskripsi' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- GRADING FORM -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                    <i class="fas fa-pen-to-square text-yellow-500"></i>
                    Form Penilaian
                </h2>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('guru.grades.update', $submission) }}">
                    @csrf
                    @method('PUT')

                    <!-- SCORE FIELD -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-900 mb-2">
                            Nilai (0-100) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="score" 
                            id="score"
                            min="0"
                            max="100"
                            step="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition"
                            value="{{ old('score', $submission->grade->score ?? '') }}" 
                            placeholder="Masukkan nilai 0-100"
                            required
                        >
                        <p class="text-gray-600 text-xs mt-2">Masukkan nilai siswa untuk tugas ini</p>
                    </div>

                    <!-- FEEDBACK FIELD -->
                    <div class="mb-8">
                        <label class="block font-semibold text-gray-900 mb-2">
                            Komentar/Feedback
                        </label>
                        <textarea 
                            name="feedback" 
                            id="feedback"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition resize-none"
                            rows="4"
                            placeholder="Berikan feedback atau komentar untuk siswa..."
                        >{{ old('feedback', $submission->grade->feedback ?? '') }}</textarea>
                        <p class="text-gray-600 text-xs mt-2">Feedback akan ditampilkan kepada siswa</p>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <a href="{{ url()->previous() ?? route('guru.grades.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-6 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="flex-1 bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
