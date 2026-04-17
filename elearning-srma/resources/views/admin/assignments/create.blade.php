@extends('layouts.admin')

@section('title', 'Tambah Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.assignments.index') }}" class="hover:text-red-600 transition">Tugas</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Tambah Tugas</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                <i class="fas fa-plus-circle"></i>
            </span>
            Tambah Tugas Baru
        </h1>
        <p class="text-gray-600 mt-2">Buat tugas baru untuk kelas dan mata pelajaran tertentu</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-pen-to-square"></i>
                Form Tambah Tugas
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form action="{{ route('admin.assignments.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <!-- Kelas & Mata Pelajaran Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="e_class_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kelas <span class="text-red-600">*</span>
                        </label>
                        <select name="e_class_id" id="e_class_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('e_class_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('e_class_id') == $class->id)>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('e_class_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="class_subject_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mata Pelajaran <span class="text-red-600">*</span>
                        </label>
                        <select name="class_subject_id" id="class_subject_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('class_subject_id') border-red-500 @enderror">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                        </select>
                        @error('class_subject_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Judul Tugas -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Judul Tugas <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="title" id="title" placeholder="Masukkan judul tugas" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('title') border-red-500 @enderror" 
                        value="{{ old('title') }}" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi <span class="text-red-600">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" placeholder="Masukkan deskripsi tugas" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition resize-none @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deadline <span class="text-red-600">*</span>
                    </label>
                    <input type="datetime-local" name="deadline" id="deadline" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('deadline') border-red-500 @enderror" 
                        value="{{ old('deadline') }}" required>
                    @error('deadline')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File (Opsional) -->
                <div>
                    <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">
                        File Soal (Opsional)
                    </label>
                    <input type="file" name="file" id="file"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('file') border-red-500 @enderror"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.jpg,.jpeg,.png">
                    <p class="text-xs text-gray-500 mt-1">Boleh dikosongkan. Jika diisi, file akan disimpan sebagai lampiran tugas.</p>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.assignments.index') }}" class="inline-flex items-center gap-2 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="ml-auto inline-flex items-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                        <i class="fas fa-save"></i> Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('e_class_id').addEventListener('change', function() {
    const classId = this.value;
    const subjectSelect = document.getElementById('class_subject_id');
    subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
    
    if (classId) {
        fetch(`/admin/classes/${classId}/subjects`)
            .then(response => response.json())
            .then(data => {
                data.forEach(cs => {
                    const option = document.createElement('option');
                    option.value = cs.id;
                    option.text = cs.subject.name + ' (' + cs.teacher.name + ')';
                    subjectSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>
@endsection
