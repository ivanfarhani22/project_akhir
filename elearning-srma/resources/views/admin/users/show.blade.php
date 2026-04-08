@extends('layouts.admin')

@section('title', 'Lihat Pengguna')
@section('icon', 'user')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <p class="text-gray-500 text-xs sm:text-sm mb-2">Detail Data</p>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-user text-red-500"></i>
            Detail Pengguna
        </h1>
        <p class="text-gray-500 text-xs sm:text-sm mt-2 truncate">{{ $user->name }}</p>
    </div>

    <div class="max-w-2xl">
        <!-- User Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-4 sm:py-6">
                <div class="flex items-start gap-4 sm:gap-6">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white text-red-500 flex items-center justify-center font-bold text-2xl sm:text-3xl flex-shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="text-white flex-1">
                        <h2 class="text-lg sm:text-2xl font-bold">{{ $user->name }}</h2>
                        <p class="text-red-100 text-xs sm:text-sm mt-1">{{ $user->email }}</p>
                        <div class="mt-3 flex items-center gap-2">
                            <span class="inline-block px-3 py-1 bg-white bg-opacity-20 rounded-full text-xs sm:text-sm font-semibold">
                                @switch($user->role)
                                    @case('admin_elearning')
                                        <i class="fas fa-crown"></i> Admin E-Learning
                                        @break
                                    @case('guru')
                                        <i class="fas fa-chalkboard-user"></i> Guru
                                        @break
                                    @case('siswa')
                                        <i class="fas fa-book"></i> Siswa
                                        @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Details -->
            <div class="p-3 sm:p-6 divide-y divide-gray-200">
                <!-- Name -->
                <div class="pb-4 sm:pb-6 first:pt-0">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-semibold uppercase tracking-wide mb-1">
                                <i class="fas fa-id-card text-red-500"></i> Nama Lengkap
                            </p>
                            <p class="text-gray-900 text-sm sm:text-base font-semibold">{{ $user->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="py-4 sm:py-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-semibold uppercase tracking-wide mb-1">
                                <i class="fas fa-envelope text-red-500"></i> Email
                            </p>
                            <p class="text-gray-900 text-sm sm:text-base font-semibold break-all">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Role -->
                <div class="py-4 sm:py-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-semibold uppercase tracking-wide mb-1">
                                <i class="fas fa-user-tag text-red-500"></i> Role / Peran
                            </p>
                            <div class="flex items-center gap-2">
                                @switch($user->role)
                                    @case('admin_elearning')
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs sm:text-sm font-semibold">
                                            <i class="fas fa-crown"></i> Admin E-Learning
                                        </span>
                                        @break
                                    @case('guru')
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs sm:text-sm font-semibold">
                                            <i class="fas fa-chalkboard-user"></i> Guru
                                        </span>
                                        @break
                                    @case('siswa')
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs sm:text-sm font-semibold">
                                            <i class="fas fa-book"></i> Siswa
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Verified -->
                <div class="py-4 sm:py-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-semibold uppercase tracking-wide mb-1">
                                <i class="fas fa-shield-check text-red-500"></i> Verifikasi Email
                            </p>
                            @if($user->email_verified_at)
                                <div class="flex items-center gap-2 text-green-700 font-semibold text-sm sm:text-base">
                                    <i class="fas fa-check-circle"></i> Terverifikasi pada {{ $user->email_verified_at->format('d M Y H:i') }}
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-gray-600 font-semibold text-sm sm:text-base">
                                    <i class="fas fa-times-circle"></i> Belum Terverifikasi
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Created At -->
                <div class="py-4 sm:py-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-semibold uppercase tracking-wide mb-1">
                                <i class="fas fa-calendar-plus text-red-500"></i> Dibuat Pada
                            </p>
                            <p class="text-gray-900 text-sm sm:text-base font-semibold">{{ $user->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Updated At -->
                <div class="py-4 sm:py-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-semibold uppercase tracking-wide mb-1">
                                <i class="fas fa-calendar-check text-red-500"></i> Diperbarui Pada
                            </p>
                            <p class="text-gray-900 text-sm sm:text-base font-semibold">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-200 flex gap-3 sm:gap-4 flex-wrap">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 bg-gray-300 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-700 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
