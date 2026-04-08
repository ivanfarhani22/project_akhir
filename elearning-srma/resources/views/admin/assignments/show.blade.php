@extends('layouts.admin')

@section('title', 'Detail Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.assignments.index') }}" class="hover:text-red-600 transition">Tugas</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Detail Tugas</span>
    </nav>

    <!-- Header with Actions -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-eye"></i>
                </span>
                Detail Tugas
            </h1>
            <p class="text-gray-600 mt-2">{{ $assignment->title }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.assignments.edit', $assignment) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-semibold">
                <i class="fas fa-pencil"></i> Edit
            </a>
            <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" class="inline delete-form">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(event, '{{ $assignment->title }}')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
            <a href="{{ route('admin.assignments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Assignment Details & Submissions (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assignment Details Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                    <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Detail Tugas
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                            <span class="text-gray-700 font-semibold">Kelas:</span>
                            <span class="inline-block px-4 py-2 bg-red-100 text-red-700 rounded-full font-semibold">
                                {{ $assignment->classSubject->eClass->name }}
                            </span>
                        </div>

                        <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                            <span class="text-gray-700 font-semibold">Mata Pelajaran:</span>
                            <span class="text-gray-900">{{ $assignment->classSubject->subject->name }}</span>
                        </div>

                        <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                            <span class="text-gray-700 font-semibold">Guru:</span>
                            <span class="text-gray-900">{{ $assignment->classSubject->teacher->name }}</span>
                        </div>

                        <div class="pb-4 border-b border-gray-200">
                            <span class="text-gray-700 font-semibold block mb-2">Deskripsi:</span>
                            <p class="text-gray-900 leading-relaxed">{{ $assignment->description }}</p>
                        </div>

                        <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                            <span class="text-gray-700 font-semibold">Deadline:</span>
                            <span class="@if($assignment->deadline && now() > $assignment->deadline) text-red-600 font-semibold @else text-gray-900 @endif">
                                @if($assignment->deadline)
                                    {{ $assignment->deadline->format('d M Y H:i') }}
                                @else
                                    <span class="text-gray-500">Tidak ada deadline</span>
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                            <span class="text-gray-700 font-semibold">Nilai Maksimal:</span>
                            <span class="text-gray-900 font-semibold">{{ $assignment->max_score }}</span>
                        </div>

                        <div class="flex justify-between items-start">
                            <span class="text-gray-700 font-semibold">Dibuat:</span>
                            <span class="text-gray-900">{{ $assignment->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submissions Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                    <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                        <i class="fas fa-file-upload"></i>
                        Submissions ({{ $submissions->count() }})
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    @if($submissions->count() > 0)
                        <table class="w-full border-collapse">
                            <thead class="bg-gray-100">
                                <tr class="border-b-2 border-gray-300">
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Siswa</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-40">Tanggal Submit</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-24">Nilai</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ $submission->student->name }}</td>
                                        <td class="px-6 py-4">
                                            @if($submission->submitted_at)
                                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                    Submitted
                                                </span>
                                            @else
                                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                                    Belum kirim
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            @if($submission->submitted_at)
                                                {{ $submission->submitted_at->format('d M Y H:i') }}
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold">
                                            @if($submission->grade)
                                                <span class="text-red-600">{{ $submission->grade->score }}</span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <button onclick="toggleGradeModal('gradeModal{{ $submission->id }}')" 
                                                class="inline-flex items-center gap-2 px-3 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition text-sm font-semibold">
                                                <i class="fas fa-star"></i> Nilai
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 block opacity-50"></i>
                            <p>Belum ada submission</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Statistics (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                        <i class="fas fa-chart-pie"></i>
                        Statistik
                    </h2>
                </div>

                <div class="divide-y divide-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <p class="text-gray-600 text-sm mb-2">Total Siswa</p>
                        <p class="text-3xl font-bold text-red-600">{{ $statistics['total_students'] ?? 0 }}</p>
                    </div>

                    <div class="p-6 border-b border-gray-200">
                        <p class="text-gray-600 text-sm mb-2">Sudah Submit</p>
                        <p class="text-3xl font-bold text-green-600">{{ $statistics['submitted'] ?? 0 }}</p>
                    </div>

                    <div class="p-6 border-b border-gray-200">
                        <p class="text-gray-600 text-sm mb-2">Belum Submit</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $statistics['pending'] ?? 0 }}</p>
                    </div>

                    <div class="p-6">
                        <p class="text-gray-600 text-sm mb-2">Sudah Dinilai</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $statistics['graded'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Modals -->
@foreach($submissions as $submission)
<!-- Modal {{ $submission->id }} -->
<div id="gradeModal{{ $submission->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-white font-semibold text-lg">Nilai untuk {{ $submission->student->name }}</h2>
            <button onclick="toggleGradeModal('gradeModal{{ $submission->id }}')" class="text-white hover:text-red-200 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('admin.assignments.gradeSubmission', [$assignment, $submission]) }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="score{{ $submission->id }}" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nilai (0-{{ $assignment->max_score }})
                    </label>
                    <input type="number" name="score" id="score{{ $submission->id }}" 
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition"
                        min="0" max="{{ $assignment->max_score }}" step="0.5"
                        value="{{ $submission->grade->score ?? '' }}" required>
                </div>

                <div>
                    <label for="feedback{{ $submission->id }}" class="block text-sm font-semibold text-gray-700 mb-2">
                        Feedback
                    </label>
                    <textarea name="feedback" id="feedback{{ $submission->id }}" rows="4"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition resize-none">{{ $submission->grade->feedback ?? '' }}</textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="toggleGradeModal('gradeModal{{ $submission->id }}')" 
                    class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Tutup
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
function toggleGradeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.toggle('hidden');
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.id && event.target.id.startsWith('gradeModal')) {
        event.target.classList.add('hidden');
    }
});

function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Yakin ingin menghapus tugas "${name}"?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}
</script>
@endsection
