@extends('layouts.admin')

@section('title', 'Edit Nilai')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 mb-6">
        <i class="fas fa-star text-red-600"></i>
        <span class="text-gray-600">Admin</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-600">
            <a href="{{ route('admin.grades.index') }}" class="hover:text-red-600">Kelola Nilai</a>
        </span>
        <span class="text-gray-400">/</span>
        <span class="font-semibold text-gray-800">Edit Nilai</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800 mb-2">Edit Nilai</h1>
        <p class="text-gray-600">Perbarui nilai dan feedback untuk siswa</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-edit"></i>Form Edit Nilai
            </h2>
        </div>

        <div class="p-6 sm:p-8">
            <!-- Info Box -->
            <div class="mb-6 p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                <div class="space-y-2 text-sm">
                    <p class="text-gray-700">
                        <span class="font-semibold text-blue-700">Siswa:</span>
                        <span class="text-gray-800">{{ $grade->submission->student->name }}</span>
                    </p>
                    <p class="text-gray-700">
                        <span class="font-semibold text-blue-700">Tugas:</span>
                        <span class="text-gray-800">{{ $grade->submission->assignment->title }}</span>
                    </p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.grades.update', $grade) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Score and Percentage -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="score" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-star text-red-600 mr-2"></i>Nilai <span class="text-red-600">*</span>
                        </label>
                        @php($maxScore = $grade->submission->assignment->max_score ?? 100)
                        <input type="number" name="score" id="score" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none @error('score') border-red-500 @enderror" 
                            min="0" max="{{ $maxScore }}" step="0.5"
                            value="{{ old('score', $grade->score) }}" required>
                        @error('score')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="percentage" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-percent text-red-600 mr-2"></i>Persentase
                        </label>
                        <div class="flex gap-2">
                            <input type="text" id="percentage" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" disabled>
                            <span class="flex items-center px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-semibold">%</span>
                        </div>
                    </div>
                </div>

                <!-- Feedback -->
                <div>
                    <label for="feedback" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-comment text-red-600 mr-2"></i>Feedback
                    </label>
                    <textarea name="feedback" id="feedback" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none @error('feedback') border-red-500 @enderror" 
                        rows="5" placeholder="Berikan feedback untuk siswa">{{ old('feedback', $grade->feedback) }}</textarea>
                    @error('feedback')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold px-6 py-3 rounded-lg hover:from-red-700 hover:to-red-800 transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.grades.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-3 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const scoreInput = document.getElementById('score');
const percentageInput = document.getElementById('percentage');
const maxScore = {{ $grade->submission->assignment->max_score ?? 100 }};

function updatePercentage() {
    const score = parseFloat(scoreInput.value) || 0;
    const percentage = (score / maxScore) * 100;
    percentageInput.value = percentage.toFixed(1);
}

scoreInput.addEventListener('input', updatePercentage);
updatePercentage();
</script>
@endsection
