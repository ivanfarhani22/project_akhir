@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')
@section('icon', 'fas fa-edit')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Manajemen Mata Pelajaran</p>
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Edit Mata Pelajaran
        </h1>
        <p class="page-description">{{ $subject->name }}</p>
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

    <div class="card" style="max-width: 600px;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-book" style="color: var(--primary); margin-right: 10px;"></i>
                Form Edit Mata Pelajaran
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Nama Mata Pelajaran *
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-input"
                        value="{{ old('name', $subject->name) }}" 
                        placeholder="Misal: Matematika, Bahasa Inggris"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                        required
                    >
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Kode Mata Pelajaran *
                    </label>
                    <input 
                        type="text" 
                        name="code" 
                        id="code" 
                        class="form-input"
                        value="{{ old('code', $subject->code) }}" 
                        placeholder="Misal: MAT, IPA, BIN"
                        maxlength="10"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease; text-transform: uppercase;"
                        required
                    >
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Deskripsi (Opsional)
                    </label>
                    <textarea 
                        name="description" 
                        id="description"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease; font-family: inherit; resize: vertical;"
                        rows="4"
                        placeholder="Deskripsi singkat tentang mata pelajaran ini..."
                    >{{ old('description', $subject->description) }}</textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button 
                        type="submit" 
                        class="btn btn-primary"
                        style="flex: 1; justify-content: center;"
                    >
                        <i class="fas fa-save"></i> Perbarui Mata Pelajaran
                    </button>
                    <a 
                        href="{{ route('admin.subjects.index') }}" 
                        class="btn btn-secondary"
                        style="flex: 1; justify-content: center; text-decoration: none;"
                    >
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
        }
    </style>
@endsection
