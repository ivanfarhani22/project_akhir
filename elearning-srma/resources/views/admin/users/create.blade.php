@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('icon', 'user-plus')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <p class="text-gray-500 text-sm mb-2">Tambah Data</p>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-user-plus text-red-500"></i>
            Tambah Pengguna Baru
        </h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Formulir Pendaftaran Pengguna</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            placeholder="Masukkan nama lengkap"
                            class="w-full px-4 py-2 border-2 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('name') border-red-500 @else border-gray-300 @enderror"
                            value="{{ old('name') }}" 
                            required
                        >
                        @error('name')
                            <span class="text-red-500 text-xs mt-2 block">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            placeholder="nama@sekolah.sch.id"
                            class="w-full px-4 py-2 border-2 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('email') border-red-500 @else border-gray-300 @enderror"
                            value="{{ old('email') }}" 
                            required
                        >
                        @error('email')
                            <span class="text-red-500 text-xs mt-2 block">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="Masukkan password (minimal 6 karakter)"
                            class="w-full px-4 py-2 border-2 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('password') border-red-500 @else border-gray-300 @enderror"
                            required
                        >
                        @error('password')
                            <span class="text-red-500 text-xs mt-2 block">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-900 mb-2">
                            Role / Peran <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="role" 
                            id="role" 
                            class="w-full px-4 py-2 border-2 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('role') border-red-500 @else border-gray-300 @enderror"
                            required
                        >
                            <option value="">-- Pilih Role --</option>
                            <option value="admin_elearning" @selected(old('role') === 'admin_elearning')>Admin E-Learning</option>
                            <option value="guru" @selected(old('role') === 'guru')>Guru</option>
                            <option value="siswa" @selected(old('role') === 'siswa')>Siswa</option>
                        </select>
                        @error('role')
                            <span class="text-red-500 text-xs mt-2 block">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition"
                        >
                            <i class="fas fa-save"></i> Simpan Pengguna
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 bg-gray-300 text-gray-900 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-400 transition">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
