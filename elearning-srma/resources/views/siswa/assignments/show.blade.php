@extends('layouts.siswa')

@section('title', $assignment->title)
@section('icon', 'fas fa-tasks')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <div class="flex justify-between items-start gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <i class="fas fa-tasks text-amber-500"></i>
                    {{ $assignment->title }}
                </h1>
                <p class="text-gray-600 text-sm mt-1">{{ $assignment->eClass->subject->name }} • {{ $assignment->eClass->name }}</p>
            </div>
            <a href="{{ route('siswa.assignments.index') }}" class="text-blue-500 hover:text-blue-600 font-semibold text-sm transition inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @php
        $submission = \App\Models\Submission::where('student_id', auth()->id())
            ->where('assignment_id', $assignment->id)
            ->first();
        $deadline = $assignment->deadline;
        $isLate = $submission && $submission->submitted_at && $submission->submitted_at > $deadline;
        $isOverdue = now() > $deadline && !$submission->submitted_at;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- MAIN CONTENT (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-file-alt text-blue-500"></i>
                        Deskripsi Tugas
                    </h2>
                </div>
                <div class="p-6 text-gray-700 text-base leading-relaxed">
                    {!! $assignment->description !!}
                </div>
            </div>

            <!-- Grade Card (if exists) -->
            @if($submission && $submission->grade)
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg shadow-sm border-l-4 border-amber-500 overflow-hidden">
                    <div class="px-6 py-4 bg-transparent">
                        <h2 class="font-bold text-amber-900 flex items-center gap-2">
                            <i class="fas fa-star"></i>
                            Penilaian Anda
                        </h2>
                    </div>
                    <div class="px-6 py-8 text-center">
                        <div class="text-5xl font-bold text-amber-600 mb-3">
                            {{ $submission->grade->score }}
                            @if($submission->grade->max_score)
                                <span class="text-3xl text-gray-500 font-normal">/ {{ $submission->grade->max_score }}</span>
                            @endif
                        </div>
                        @php
                            $maxScore = $submission->grade->max_score ?? 100;
                            $percentage = ($submission->grade->score / $maxScore) * 100;
                            $gradeLabel = $percentage >= 85 ? 'Sangat Baik' : ($percentage >= 70 ? 'Baik' : ($percentage >= 60 ? 'Cukup' : 'Kurang'));
                            $gradeColor = $percentage >= 85 ? 'text-green-600' : ($percentage >= 70 ? 'text-blue-600' : ($percentage >= 60 ? 'text-yellow-600' : 'text-red-600'));
                            $gradeBg = $percentage >= 85 ? 'bg-green-500' : ($percentage >= 70 ? 'bg-blue-500' : ($percentage >= 60 ? 'bg-yellow-500' : 'bg-red-500'));
                        @endphp
                        <p class="font-bold text-lg mb-4 {{ $gradeColor }}">
                            {{ $gradeLabel }}
                        </p>
                        <div class="bg-gray-300 h-2 rounded-full overflow-hidden mb-3">
                            <div class="{{ $gradeBg }} h-full rounded-full transition-all" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <p class="text-gray-600 text-sm">Persentase: {{ number_format($percentage, 1) }}%</p>

                        @if($submission->grade->feedback)
                            <div class="mt-6 pt-6 border-t border-amber-200 text-left">
                                <p class="text-amber-900 text-xs font-bold mb-2 uppercase">Komentar Guru</p>
                                <p class="text-gray-700 text-sm leading-relaxed">{{ $submission->grade->feedback }}</p>
                            </div>
                        @endif

                        @if($submission->grade->graded_at)
                            <p class="text-gray-600 text-xs mt-4 pt-4 border-t border-amber-200">
                                <i class="fas fa-check-circle"></i>
                                Dinilai pada {{ $submission->grade->graded_at->format('d M Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Submission Files -->
            @if($submission && $submission->file_path)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-paperclip text-blue-500"></i>
                            File Pengumpulan Anda
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 flex justify-between items-center gap-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file text-2xl text-blue-500"></i>
                                <div>
                                    <p class="font-bold text-gray-900">
                                        {{ basename($submission->file_path) }}
                                    </p>
                                    @if($submission->submitted_at)
                                        <p class="text-gray-600 text-xs mt-1">
                                            Dikirim: {{ $submission->submitted_at->format('d M Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition inline-flex items-center gap-2 whitespace-nowrap">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submission Form (if not submitted or deadline not passed) -->
            @if(!$submission || !$submission->submitted_at || (now()->lessThan($deadline)))
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-cloud-upload-alt text-green-500"></i>
                            Pengumpulan Tugas
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="#" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block font-semibold text-gray-900 mb-2">
                                    <i class="fas fa-file-upload mr-2"></i> Unggah File
                                </label>
                                <input type="file" name="file" class="block w-full px-4 py-2 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition focus:outline-none" required>
                                <p class="text-gray-600 text-xs mt-2">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, ZIP (Maksimal 10 MB)</p>
                            </div>

                            @if($submission && $submission->submitted_at && now()->lessThan($deadline))
                                <div class="bg-blue-50 p-3 rounded-lg border-l-4 border-blue-500">
                                    <p class="text-blue-700 text-sm">
                                        ℹ️ Anda dapat mengubah pengumpulan Anda sebelum deadline
                                    </p>
                                </div>
                            @endif

                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition text-center inline-flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane"></i> 
                                {{ $submission && $submission->submitted_at ? 'Perbarui Pengumpulan' : 'Kirim Pengumpulan' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- SIDEBAR (1/3) -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden sticky top-4">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Status Tugas
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="pb-4 border-b border-gray-200">
                        <p class="text-gray-600 text-xs font-semibold mb-2 uppercase">Status</p>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full {{ $submission && $submission->submitted_at ? ($isLate ? 'bg-orange-500' : 'bg-green-500') : ($isOverdue ? 'bg-red-500' : 'bg-blue-500') }}"></span>
                            <span class="text-gray-900 font-bold">
                                @if($submission && $submission->submitted_at)
                                    {{ $isLate ? '⚠️ Terlambat' : '✓ Terkumpul' }}
                                @elseif($isOverdue)
                                    ✗ Belum Dikumpul
                                @else
                                    ⏳ Draft
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <p class="text-gray-600 text-xs font-semibold mb-2 uppercase">Deadline</p>
                        <p class="text-gray-900 font-bold">{{ $deadline->format('d M Y') }}</p>
                        <p class="text-gray-600 text-sm">{{ $deadline->format('H:i') }} WIB</p>
                        <p class="text-sm font-bold mt-2 {{ now()->greaterThan($deadline) ? 'text-red-600' : 'text-amber-600' }}">
                            @php
                                $now = now();
                                if ($now->greaterThan($deadline)) {
                                    echo '✗ Sudah terlewat';
                                } else {
                                    $days = $now->diffInDays($deadline, false);
                                    $hours = $now->diffInHours($deadline, false) % 24;
                                    if ($days > 0) {
                                        echo "⏰ " . $days . " hari " . $hours . " jam lagi";
                                    } else {
                                        echo "⏰ " . $hours . " jam lagi";
                                    }
                                }
                            @endphp
                        </p>
                    </div>

                    @if($submission && $submission->submitted_at)
                        <div>
                            <p class="text-gray-600 text-xs font-semibold mb-2 uppercase">Waktu Pengumpulan</p>
                            <p class="text-gray-900 font-bold">{{ $submission->submitted_at->format('d M Y') }}</p>
                            <p class="text-gray-600 text-sm">{{ $submission->submitted_at->format('H:i') }} WIB</p>
                            @if($isLate)
                                <p class="text-amber-600 text-xs font-bold mt-2">⚠️ Terlambat {{ $submission->submitted_at->diffInHours($deadline) }} jam</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Class Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-book text-blue-500"></i>
                        Informasi Kelas
                    </h2>
                </div>
                <div class="p-6 space-y-3 divide-y divide-gray-200">
                    <div class="pb-3">
                        <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Mata Pelajaran</p>
                        <p class="text-gray-900 font-bold">{{ $assignment->eClass->subject->name }}</p>
                    </div>
                    
                    <div class="pt-3 pb-3">
                        <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Kelas</p>
                        <p class="text-gray-900 font-bold">{{ $assignment->eClass->name }}</p>
                    </div>

                    <div class="pt-3">
                        <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Pengajar</p>
                        <p class="text-gray-900 font-bold">{{ $assignment->eClass->teacher->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
