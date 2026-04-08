@extends('layouts.admin')

@section('title', 'Kelola Mata Pelajaran')
@section('icon', 'book')

@section('content')
    <!-- Header Section -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-book text-red-500"></i>
                Manajemen Mata Pelajaran
            </h1>
            <p class="text-gray-500 text-sm">Kelola semua mata pelajaran di sekolah</p>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
            <i class="fas fa-plus"></i> Tambah Mata Pelajaran
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.subjects.index') }}" class="flex gap-4 items-end">
            <!-- Search Input -->
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-search"></i> Cari Mata Pelajaran
                </label>
                <input type="text" name="search" placeholder="Cari nama, kode, atau deskripsi..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                <i class="fas fa-search"></i> Cari
            </button>

            <!-- Reset Button -->
            <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center gap-2 bg-gray-300 text-gray-900 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-400 transition">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>

        <!-- Info Text -->
        <small class="text-gray-500 mt-4 block">
            Menampilkan {{ $subjects->count() }} dari {{ $subjects->total() }} mata pelajaran
        </small>
    </div>

    <!-- Subjects Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($subjects->isEmpty())
            <div class="text-center py-16 px-6">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                <h3 class="text-gray-600 font-semibold mb-2">Tidak ada mata pelajaran ditemukan</h3>
                <p class="text-gray-500 mb-6">
                    @if(request('search'))
                        Coba ubah pencarian Anda atau <a href="{{ route('admin.subjects.index') }}" class="text-red-500 font-semibold hover:underline">reset filter</a>
                    @else
                        Mulai dengan <a href="{{ route('admin.subjects.create') }}" class="text-red-500 font-semibold hover:underline">membuat mata pelajaran baru</a>
                    @endif
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nama Mata Pelajaran</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Kode</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Deskripsi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($subjects as $subject)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-book text-red-500"></i> {{ $subject->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold px-3 py-1 rounded">
                                        {{ $subject->code }}
                                    </code>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    {{ Str::limit($subject->description ?? '-', 60) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.subjects.edit', $subject) }}" class="inline-flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded text-xs font-semibold hover:bg-blue-600 transition">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
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
            @if($subjects->hasPages())
                <div class="flex justify-center items-center gap-4 py-8 px-6 border-t border-gray-200">
                    <!-- Previous Button -->
                    @if($subjects->onFirstPage())
                        <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-400 px-4 py-2 rounded-lg font-semibold text-sm cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                    @else
                        <a href="{{ $subjects->previousPageUrl() }}" class="inline-flex items-center gap-2 bg-gray-300 text-gray-900 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-400 transition">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </a>
                    @endif

                    <!-- Page Info -->
                    <div class="px-4 py-2 bg-gray-100 rounded-lg text-sm text-gray-700 font-semibold">
                        Halaman {{ $subjects->currentPage() }} dari {{ $subjects->lastPage() }}
                    </div>

                    <!-- Next Button -->
                    @if($subjects->hasMorePages())
                        <a href="{{ $subjects->nextPageUrl() }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
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

