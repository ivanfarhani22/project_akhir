@extends('layouts.guru')
@section('title', 'Edit Tugas')
@section('icon', 'fas fa-edit')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-edit mr-1"></i> Guru / Tugas / Edit</p>
    <h1 class="text-2xl font-extrabold text-gray-900">{{ $assignment->title }}</h1>
    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
        <i class="fas fa-pen"></i> Edit tugas
    </span>
</div>

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Form Edit Tugas</h2>
        </div>
        <div class="p-6">
            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <p class="text-sm text-blue-800">Edit hanya mengubah tugas ini. Siswa yang sudah mengumpulkan tetap memiliki submission mereka.</p>
            </div>

            <form method="POST" action="{{ route('guru.assignments.update', $assignment) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Tugas <span class="text-red-500">*</span></label>
                    <input type="text" name="title"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                        value="{{ old('title', $assignment->title) }}" required>
                    @error('title')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none">{{ old('description', $assignment->description) }}</textarea>
                    @error('description')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Batas Waktu <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="deadline"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                        value="{{ old('deadline', $assignment->deadline->format('Y-m-d\TH:i')) }}" required>
                    @error('deadline')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Soal <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    @if($assignment->file_path)
                        @php $downloadUrl = str_starts_with($assignment->file_path,'storage/') ? asset($assignment->file_path) : asset('storage/'.ltrim($assignment->file_path,'/')); @endphp
                        <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-800"><i class="fas fa-file mr-2 text-gray-400"></i>File Saat Ini</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ strtoupper(pathinfo($assignment->file_path, PATHINFO_EXTENSION)) }}</p>
                            </div>
                            <a href="{{ $downloadUrl }}" target="_blank"
                               class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                <i class="fas fa-download text-[10px]"></i> Download
                            </a>
                        </div>
                    @endif
                    <div id="dropzone" class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center bg-gray-50 hover:border-[#A41E35] hover:bg-red-50 transition cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 mb-3 block"></i>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Klik atau drag file di sini</p>
                        <p class="text-xs text-gray-400">Biarkan kosong jika tidak ingin mengubah file</p>
                        <input type="file" name="file" id="file" class="hidden">
                    </div>
                    <p class="text-emerald-600 text-xs mt-2 font-medium" id="filename"></p>
                    @error('file')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ url()->previous() ?? route('guru.assignments.index') }}"
                       class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Update Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const dropzone = document.getElementById('dropzone'), fileInput = document.getElementById('file'), filename = document.getElementById('filename');
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('border-[#A41E35]','bg-red-50'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-[#A41E35]','bg-red-50'));
    dropzone.addEventListener('drop', e => { e.preventDefault(); dropzone.classList.remove('border-[#A41E35]','bg-red-50'); fileInput.files = e.dataTransfer.files; updateFilename(); });
    fileInput.addEventListener('change', updateFilename);
    function updateFilename() { const f = fileInput.files[0]; if (f) filename.textContent = `✓ ${f.name} (${(f.size/1048576).toFixed(2)} MB)`; }
</script>
@endsection