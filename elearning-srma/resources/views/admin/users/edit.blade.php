@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('icon', 'user-edit')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <p class="text-gray-500 text-xs sm:text-sm mb-2">Edit Data</p>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-user-edit text-red-500"></i>
            Edit Pengguna
        </h1>
        <p class="text-gray-500 text-xs sm:text-sm mt-2 truncate">{{ $user->name }}</p>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-bold text-gray-900">Formulir Edit Pengguna</h2>
            </div>
            <div class="p-3 sm:p-6">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition @error('name') border-red-500 @else border-gray-300 @enderror"
                            value="{{ $user->name }}" 
                            required
                        >
                        @error('name')
                            <span class="text-red-500 text-xs mt-2 block">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition @error('email') border-red-500 @else border-gray-300 @enderror"
                            value="{{ $user->email }}" 
                            required
                        >
                        @error('email')
                            <span class="text-red-500 text-xs mt-2 block">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Password Baru
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            placeholder="Kosongkan jika tidak ingin mengubah password"
                            class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition @error('password') border-red-500 @else border-gray-300 @enderror"
                        >
                        <p class="text-gray-500 text-xs mt-2">
                            Biarkan kosong jika tidak ingin mengubah password
                        </p>
                        @error('password')
                            <span class="text-red-500 text-xs mt-2 block">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Role / Peran <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="role" 
                            id="role" 
                            class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition @error('role') border-red-500 @else border-gray-300 @enderror"
                            required
                        >
                            <option value="admin_elearning" @selected($user->role === 'admin_elearning')>Admin E-Learning</option>
                            <option value="guru" @selected($user->role === 'guru')>Guru</option>
                            <option value="siswa" @selected($user->role === 'siswa')>Siswa</option>
                        </select>
                        @error('role')
                            <span class="text-red-500 text-xs mt-2 block">❌ {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition"
                        >
                            <i class="fas fa-save"></i> <span class="hidden sm:inline">Perbarui Pengguna</span><span class="sm:hidden">Perbarui</span>
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-300 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-400 transition">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
