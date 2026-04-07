@extends('layouts.guru')

@section('title', 'Buat Tugas Baru')
@section('icon', 'fas fa-plus-circle')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Manajemen Tugas</p>
        <h1 class="page-title">
            <i class="fas fa-plus-circle"></i>
            Buat Tugas Baru
        </h1>
        <p class="page-description">Kelas: <strong>{{ $class->name }}</strong> • {{ $class->subject->name }}</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card" style="max-width: 700px;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-tasks" style="color: var(--primary); margin-right: 10px;"></i>
                Form Buat Tugas
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('guru.assignments.store') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="e_class_id" value="{{ $class->id }}">

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Judul Tugas *
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        class="form-input"
                        value="{{ old('title') }}" 
                        placeholder="Contoh: Latihan Soal Chapter 5 - Fungsi Kuadrat"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                        required
                    >
                    <small style="color: #999; margin-top: 5px; display: block;">Berikan judul yang deskriptif untuk tugas ini</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Deskripsi
                    </label>
                    <textarea 
                        name="description" 
                        id="description"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease; font-family: inherit; resize: vertical;"
                        rows="4"
                        placeholder="Jelaskan detail tugas, instruksi, dan kriteria penilaian..."
                    >{{ old('description') }}</textarea>
                    <small style="color: #999; margin-top: 5px; display: block;">Tuliskan penjelasan lengkap untuk siswa</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Batas Waktu *
                    </label>
                    <input 
                        type="datetime-local" 
                        name="deadline" 
                        id="deadline"
                        class="form-input"
                        value="{{ old('deadline') }}" 
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                        required
                    >
                    <small style="color: #999; margin-top: 5px; display: block;">Tentukan kapan siswa harus mengumpulkan tugas</small>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 10px; color: var(--secondary);">
                        File Tugas (Opsional)
                    </label>
                    <div style="position: relative; border: 2px dashed var(--border); border-radius: 12px; padding: 40px 20px; text-align: center; background: #fafafa; cursor: pointer; transition: all 0.3s ease;" id="dropzone">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: var(--primary); margin-bottom: 15px; display: block;"></i>
                        <p style="font-weight: 600; color: var(--secondary); margin-bottom: 5px;">Klik atau drag file di sini</p>
                        <p style="color: #999; font-size: 13px; margin-bottom: 10px;">PDF, DOC, XLS, PPT, ZIP (Max 10MB)</p>
                        <p style="color: #bbb; font-size: 12px;">Gunakan file sebagai referensi atau template untuk tugas</p>
                        <input 
                            type="file" 
                            name="file" 
                            id="file"
                            style="display: none;"
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
                        style="flex: 1; justify-content: center;"
                    >
                        <i class="fas fa-save"></i> Buat Tugas
                    </button>
                    <a 
                        href="{{ route('guru.assignments.index', ['class_id' => $class->id]) }}" 
                        class="btn btn-secondary"
                        style="flex: 1; justify-content: center; text-decoration: none;"
                    >
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
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
            dropzone.style.background = 'rgba(196, 30, 58, 0.05)';
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.style.borderColor = 'var(--border)';
            dropzone.style.background = '#fafafa';
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = 'var(--border)';
            dropzone.style.background = '#fafafa';
            
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                updateFilename();
            }
        });

        fileInput.addEventListener('change', updateFilename);

        function updateFilename() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                filename.textContent = '✓ File dipilih: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            } else {
                filename.textContent = '';
            }
        }
    </script>

    <style>
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
        }
    </style>
@endsection
