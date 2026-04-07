@extends('layouts.guru')

@section('title', 'Upload Materi Baru')
@section('icon', 'fas fa-book')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Upload Materi</p>
        <h1 class="page-title">
            <i class="fas fa-cloud-upload-alt"></i>
            Tambah Materi Pembelajaran
        </h1>
        <p class="page-description">Kelas: <strong>{{ $class->name }}</strong> • {{ $class->subject->name }}</p>
    </div>

    <div style="max-width: 700px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Formulir Upload Materi</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('guru.materials.store') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="e_class_id" value="{{ $class->id }}">

                    <div style="margin-bottom: 20px;">
                        <label for="title" style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                            Judul Materi <span style="color: var(--primary);">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title"
                            placeholder="Contoh: Bab 1 - Pengenalan Sistem"
                            style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;"
                            value="{{ old('title') }}" 
                            required
                        >
                        @error('title')
                            <span style="color: var(--danger); font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="description" style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                            Deskripsi
                        </label>
                        <textarea 
                            name="description" 
                            id="description"
                            placeholder="Jelaskan materi ini untuk siswa..."
                            style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; min-height: 100px; resize: vertical;"
                            rows="4"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <span style="color: var(--danger); font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 30px;">
                        <label for="file" style="display: block; font-weight: 600; margin-bottom: 10px; color: var(--secondary);">
                            File Materi <span style="color: var(--primary);">*</span>
                        </label>
                        <div style="position: relative; border: 2px dashed var(--border); border-radius: 12px; padding: 40px 20px; text-align: center; background: #fafafa; cursor: pointer; transition: all 0.3s ease;" id="dropzone">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: var(--primary); margin-bottom: 15px; display: block;"></i>
                            <p style="font-weight: 600; color: var(--secondary); margin-bottom: 5px;">Klik atau drag file di sini</p>
                            <p style="color: #999; font-size: 13px; margin-bottom: 10px;">PDF, DOC, XLS, PPT, ZIP (Max 10MB)</p>
                            <p style="color: #bbb; font-size: 12px;">Format yang didukung: PDF, DOCX, XLSX, PPTX, ZIP, RAR</p>
                            <input 
                                type="file" 
                                name="file" 
                                id="file"
                                style="display: none;"
                                required
                            >
                        </div>
                        <p style="color: #28a745; font-size: 12px; margin-top: 10px;" id="filename"></p>
                        @error('file')
                            <span style="color: var(--danger); font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                        >
                            <i class="fas fa-upload"></i> Upload Materi
                        </button>
                        <a href="{{ route('guru.materials.index', ['class_id' => $class->id]) }}" class="btn btn-secondary" style="text-decoration: none;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file');
        const filename = document.getElementById('filename');

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = 'var(--primary)';
            dropzone.style.background = '#fff5f5';
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.style.borderColor = 'var(--border)';
            dropzone.style.background = '#fafafa';
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = 'var(--border)';
            dropzone.style.background = '#fafafa';
            fileInput.files = e.dataTransfer.files;
            updateFilename();
        });

        fileInput.addEventListener('change', updateFilename);

        function updateFilename() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                filename.textContent = `✓ File dipilih: ${file.name} (${sizeMB} MB)`;
            } else {
                filename.textContent = '';
            }
        }
    </script>
@endsection
