@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('icon', 'user-plus')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Tambah Data</p>
        <h1 class="page-title">
            <i class="fas fa-user-plus"></i>
            Tambah Pengguna Baru
        </h1>
    </div>

    <div style="max-width: 600px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Formulir Pendaftaran Pengguna</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div style="margin-bottom: 20px;">
                        <label for="name" style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                            Nama Lengkap <span style="color: var(--primary);">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            placeholder="Masukkan nama lengkap"
                            style="width: 100%; padding: 10px 12px; border: 2px solid @error('name') var(--danger) @else var(--border) @enderror; border-radius: 6px; font-size: 14px;"
                            value="{{ old('name') }}" 
                            required
                        >
                        @error('name')
                            <span style="color: var(--danger); font-size: 12px; margin-top: 5px; display: block;">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="email" style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                            Email <span style="color: var(--primary);">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            placeholder="nama@sekolah.sch.id"
                            style="width: 100%; padding: 10px 12px; border: 2px solid @error('email') var(--danger) @else var(--border) @enderror; border-radius: 6px; font-size: 14px;"
                            value="{{ old('email') }}" 
                            required
                        >
                        @error('email')
                            <span style="color: var(--danger); font-size: 12px; margin-top: 5px; display: block;">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="password" style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                            Password <span style="color: var(--primary);">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="Masukkan password (minimal 6 karakter)"
                            style="width: 100%; padding: 10px 12px; border: 2px solid @error('password') var(--danger) @else var(--border) @enderror; border-radius: 6px; font-size: 14px;"
                            required
                        >
                        @error('password')
                            <span style="color: var(--danger); font-size: 12px; margin-top: 5px; display: block;">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 30px;">
                        <label for="role" style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                            Role / Peran <span style="color: var(--primary);">*</span>
                        </label>
                        <select 
                            name="role" 
                            id="role" 
                            style="width: 100%; padding: 10px 12px; border: 2px solid @error('role') var(--danger) @else var(--border) @enderror; border-radius: 6px; font-size: 14px;"
                            required
                        >
                            <option value="">-- Pilih Role --</option>
                            <option value="admin_elearning" @selected(old('role') === 'admin_elearning')>Admin E-Learning</option>
                            <option value="guru" @selected(old('role') === 'guru')>Guru</option>
                            <option value="siswa" @selected(old('role') === 'siswa')>Siswa</option>
                        </select>
                        @error('role')
                            <span style="color: var(--danger); font-size: 12px; margin-top: 5px; display: block;">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                        >
                            <i class="fas fa-save"></i> Simpan Pengguna
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="text-decoration: none;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
