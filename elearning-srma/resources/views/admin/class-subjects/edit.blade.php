@extends('layouts.admin')

@section('title', 'Edit Guru Mata Pelajaran')
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
        <span style="color: var(--secondary); font-weight: 600;">Edit Guru</span>
    </div>

    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Edit Guru {{ $classSubject->subject->name }}
        </h1>
        <p class="page-description">Ubah guru pengajar untuk mata pelajaran ini</p>
    </div>

    @if ($errors->any())
        <div style="background: #fee; border: 2px solid #fcc; border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #c33;">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Terjadi kesalahan:</strong>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card" style="max-width: 600px;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-user-tie" style="color: var(--primary); margin-right: 10px;"></i>
                Informasi Guru
            </div>
        </div>

        <div class="card-body">
            <!-- Current Subject Info -->
            <div style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 25px; border-left: 4px solid var(--primary);">
                <p style="margin: 0; font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                    Mata Pelajaran
                </p>
                <p style="margin: 8px 0 0 0; font-size: 16px; font-weight: 700; color: var(--secondary);">
                    {{ $classSubject->subject->name }}
                </p>
                @if ($classSubject->subject->code)
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">
                        Kode: {{ $classSubject->subject->code }}
                    </p>
                @endif
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.class-subjects.update', $classSubject) }}">
                @csrf
                @method('PUT')

                <!-- Guru Pengajar (dengan Search Select2) -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Guru Pengajar <span style="color: var(--danger);">*</span>
                    </label>
                    <select 
                        name="teacher_id" 
                        id="teacherSelect"
                        style="width: 100%; padding: 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px;"
                        required
                    >
                        <option value="">-- Cari & Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $classSubject->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #999; margin-top: 5px; display: block;">
                        Guru saat ini: <strong>{{ $classSubject->teacher->name }}</strong>
                    </small>
                </div>

                <!-- Deskripsi -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Deskripsi (Opsional)
                    </label>
                    <textarea 
                        name="description" 
                        id="description"
                        style="width: 100%; padding: 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"
                        rows="3"
                        placeholder="Deskripsi singkat tentang mata pelajaran ini di kelas..."
                    >{{ old('description', $classSubject->description) }}</textarea>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; justify-content: space-between;">
                    <a 
                        href="{{ route('admin.classes.show', $classSubject->eClass) }}" 
                        class="btn btn-secondary"
                        style="text-decoration: none;"
                    >
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
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                        >
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

        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #5a5fd8;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }
    </style>
@endsection

