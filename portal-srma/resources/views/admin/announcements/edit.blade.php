@extends('layouts.admin')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

@section('content')
<form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Pengumuman <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('title') border-red-500 @enderror">
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Isi Pengumuman <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="10" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Pengaturan</h3>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $announcement->status === 'published') ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan</span>
                    </label>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_important" value="1" {{ old('is_important', $announcement->is_important) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Tandai sebagai penting</span>
                    </label>
                </div>
                
                <div class="mb-6">
                    <label for="expired_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kedaluwarsa</label>
                    <input type="date" name="expired_at" id="expired_at" value="{{ old('expired_at', $announcement->expired_at?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Update
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">File Lampiran</h3>
                
                @if($announcement->attachment)
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 min-w-0">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <span class="text-sm text-gray-600 truncate">{{ $announcement->attachment_name ?? basename($announcement->attachment) }}</span>
                        </div>
                        <a href="{{ Storage::url($announcement->attachment) }}" target="_blank" class="text-primary-600 hover:underline text-sm flex-shrink-0 ml-2">Lihat</a>
                    </div>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" name="remove_attachment" value="1" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="ml-2 text-sm text-red-600">Hapus lampiran</span>
                    </label>
                </div>
                @endif
                
                <div>
                    <input type="file" name="attachment" id="attachment" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-2">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max 5MB)</p>
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti</p>
                    @error('attachment')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
