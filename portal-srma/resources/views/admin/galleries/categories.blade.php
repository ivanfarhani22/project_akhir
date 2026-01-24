@extends('layouts.admin')

@section('title', 'Kategori Galeri')
@section('page-title', 'Kategori Galeri')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Kategori</h2>
                <a href="{{ route('admin.galleries.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali ke Galeri</a>
            </div>
        </div>
        
        <!-- Add Category Form -->
        <div class="p-6 border-b border-gray-100">
            <form action="{{ route('admin.galleries.categories.store') }}" method="POST" class="flex gap-4">
                @csrf
                <input type="text" name="name" required placeholder="Nama kategori baru"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Tambah
                </button>
            </form>
            @error('name')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Categories List -->
        <div class="divide-y divide-gray-200">
            @forelse($categories as $category)
            <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                <div>
                    <h3 class="font-medium text-gray-800">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->galleries_count }} foto</p>
                </div>
                <form action="{{ route('admin.galleries.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Foto dalam kategori ini tidak akan terhapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-red-500 hover:text-red-700" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                Belum ada kategori.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
