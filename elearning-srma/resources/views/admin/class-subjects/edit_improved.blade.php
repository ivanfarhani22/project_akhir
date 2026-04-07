@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')
@section('icon', 'fas fa-edit')

@section('content')
    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; font-size: 14px;">
        <a href="{{ route('admin.classes.index') }}" style="color: var(--primary); text-decoration: none;">
            <i class="fas fa-chalkboard"></i> Kelola Kelas
        </a>
        <span style="color: #999;">/</span>
        <a href="{{ route('admin.classes.show', $classSubject->eClass) }}" style="color: var(--primary); text-decoration: none;">
            {{ $classSubject->eClass->name }}
        </a>
        <span style="color: #999;">/</span>
        <span style="color: var(--secondary); font-weight: 600;">Edit {{ $classSubject->subject->name }}</span>
    </div>

    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Edit Mata Pelajaran: {{ $classSubject->subject->name }}
        </h1>
        <p class="page-description">Ubah guru pengajar atau deskripsi mata pelajaran</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div style="background: #fee; border: 2px solid #fcc; border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #c33;">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Terjadi kesalahan:</strong>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Form Card -->
    <div class="card" style="max-width: 600px;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-form"></i> Form Edit Mata Pelajaran</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.class-subjects.update', $classSubject) }}">
                @csrf
                @method('PUT')

                <!-- Mata Pelajaran (Read-only) -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Mata Pelajaran
                    </label>
                    <div style="padding: 12px; background: #f5f5f5; border: 2px solid var(--border); border-radius: 8px; color: #666;">
                        <i class="fas fa-book"></i> {{ $classSubject->subject->name }} ({{ $classSubject->subject->code }})
                    </div>
                </div>

                <!-- Guru (dengan Search Select2) -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Pilih Guru <span style="color: var(--danger);">*</span>
                    </label>
                    <select name="teacher_id" id="teacherSelect" class="form-select" required style="width: 100%; padding: 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px;">
                        <option value="">-- Cari & Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" 
                                {{ $teacher->id == $classSubject->teacher_id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #999; margin-top: 5px; display: block;">Guru yang akan mengajar mata pelajaran ini</small>
                </div>

                <!-- Deskripsi -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Deskripsi
                    </label>
                    <textarea name="description" style="width: 100%; padding: 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;" rows="3" placeholder="Deskripsi tentang mata pelajaran ini...">{{ old('description', $classSubject->description) }}</textarea>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; justify-content: space-between;">
                    <a href="{{ route('admin.classes.show', $classSubject->eClass) }}" class="btn btn-secondary" style="text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <div style="display: flex; gap: 10px;">
                        <form method="POST" action="{{ route('admin.class-subjects.destroy', $classSubject) }}" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- CDN Select2 & Styles -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for Teacher
            $('#teacherSelect').select2({
                placeholder: 'Cari & Pilih Guru',
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <style>
        .select2-container--default .select2-selection--single {
            border: 2px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            height: auto !important;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .select2-dropdown {
            border: 2px solid var(--border);
            border-radius: 8px;
        }

        .select2-results__option--highlighted {
            background-color: var(--primary) !important;
        }

        .form-select {
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
@endsection
