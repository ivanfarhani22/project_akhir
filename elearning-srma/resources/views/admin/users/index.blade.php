@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('icon', 'users')

@section('content')
    <!-- Header Section -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-users text-red-500"></i>
                Manajemen Pengguna
            </h1>
            <p class="text-gray-500 text-sm">Kelola guru, siswa, dan admin elearning</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-4 items-end flex-wrap">
            <!-- Search Input -->
            <div class="flex-1 min-w-xs">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-search"></i> Cari Pengguna
                </label>
                <input type="text" name="search" placeholder="Cari nama atau email..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
            </div>

            <!-- Role Filter -->
            <div class="w-48">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-filter"></i> Role
                </label>
                <select name="role" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
                    <option value="">-- Semua Role --</option>
                    <option value="admin_elearning" {{ request('role') === 'admin_elearning' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                <i class="fas fa-search"></i> Cari
            </button>

            <!-- Reset Button -->
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 bg-gray-200 text-gray-900 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>

        <!-- Info Text -->
        <p class="text-gray-500 text-sm mt-4">
            Menampilkan <strong>{{ $users->count() }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
        </p>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($users->isEmpty())
            <div class="text-center py-16 px-6">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                <h3 class="text-gray-600 font-semibold mb-2">Tidak ada pengguna ditemukan</h3>
                <p class="text-gray-500 mb-6">
                    @if(request('search') || request('role'))
                        Coba ubah pencarian atau filter Anda
                    @else
                        Mulai dengan <a href="{{ route('admin.users.create') }}" class="text-red-500 font-semibold hover:underline">membuat pengguna baru</a>
                    @endif
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nama Pengguna</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Role</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-700 text-white flex items-center justify-center font-bold text-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="bg-gray-100 text-gray-900 text-xs px-2 py-1 rounded">
                                        {{ $user->email }}
                                    </code>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->role === 'admin_elearning')
                                        <span class="inline-flex items-center gap-2 bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-shield-alt"></i> Admin
                                        </span>
                                    @elseif ($user->role === 'guru')
                                        <span class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-chalkboard-user"></i> Guru
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-user-graduate"></i> Siswa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded text-xs font-semibold hover:bg-blue-600 transition">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded text-xs font-semibold hover:bg-red-600 transition">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="flex justify-center items-center gap-4 py-8 px-6 border-t border-gray-200">
                    <!-- Previous Button -->
                    @if($users->onFirstPage())
                        <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-400 px-4 py-2 rounded-lg font-semibold text-sm cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="inline-flex items-center gap-2 bg-gray-300 text-gray-900 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-400 transition">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </a>
                    @endif

                    <!-- Page Info -->
                    <div class="px-4 py-2 bg-gray-100 rounded-lg text-sm text-gray-700 font-semibold">
                        Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}
                    </div>

                    <!-- Next Button -->
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-400 px-4 py-2 rounded-lg font-semibold text-sm cursor-not-allowed">
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection

