@extends('layouts.admin')

@section('title', 'Tambah Pengumuman')
@section('page-title', 'Tambah Pengumuman')

@section('content')
<form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Pengumuman <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('title') border-red-500 @enderror">
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Isi Pengumuman <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="10" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
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
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan</span>
                    </label>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_important" value="1" {{ old('is_important') ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Tandai sebagai penting</span>
                    </label>
                </div>
                
                <div class="mb-6">
                    <label for="expired_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kedaluwarsa</label>
                    <input type="date" name="expired_at" id="expired_at" value="{{ old('expired_at') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batas waktu</p>
                </div>
                
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Simpan
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">File Lampiran</h3>
                
                <div>
                    <input type="file" name="attachment" id="attachment" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-2">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max 5MB)</p>
                    @error('attachment')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
