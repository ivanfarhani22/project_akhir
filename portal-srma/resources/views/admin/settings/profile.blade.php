@extends('layouts.admin')

@section('title', 'Profil Sekolah')
@section('page-title', 'Profil Sekolah')

@section('content')
<form action="{{ route('admin.settings.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Dasar Hukum dan Legalitas -->
        <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Dasar Hukum dan Legalitas
            </h2>
            <p class="text-sm text-gray-500 mb-4">Masukkan dasar hukum dan dokumen legalitas sekolah. Pisahkan setiap poin dengan baris baru.</p>
            <textarea name="dasar_hukum" rows="8"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                      placeholder="Contoh:
SK Pendirian: No. XXX/XXX/2020
SK Izin Operasional: No. XXX/XXX/2021
Akreditasi: A (Amat Baik)
NSS: XXXXXXXXXX
NPSN: XXXXXXXX">{{ $profiles['dasar_hukum'] ?? '' }}</textarea>
        </div>
        
        <!-- Visi -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Visi
            </h2>
            <textarea name="visi" rows="4"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                      placeholder="Visi sekolah...">{{ $profiles['visi'] ?? '' }}</textarea>
        </div>
        
        <!-- Misi -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Misi
            </h2>
            <textarea name="misi" rows="4"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                      placeholder="Misi sekolah (pisahkan dengan enter untuk setiap poin)...">{{ $profiles['misi'] ?? '' }}</textarea>
            <p class="text-xs text-gray-500 mt-2">Pisahkan setiap misi dengan enter untuk membuat daftar</p>
        </div>
        
        <!-- Struktur Organisasi - Upload Gambar -->
        <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Struktur Organisasi
            </h2>
            
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Current Image Preview -->
                <div>
                    @if($profiles['struktur_organisasi_image'] ?? false)
                    <div class="mb-4 relative" x-data="{ showDelete: false }">
                        <p class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</p>
                        <div class="relative inline-block" @mouseenter="showDelete = true" @mouseleave="showDelete = false">
                            <img src="{{ asset('storage/' . $profiles['struktur_organisasi_image']) }}" 
                                 alt="Struktur Organisasi" 
                                 class="max-w-full h-auto rounded-lg border border-gray-200 max-h-64 object-contain">
                            
                            <!-- Delete overlay -->
                            <div x-show="showDelete" 
                                 x-transition
                                 class="absolute inset-0 bg-black/50 rounded-lg flex items-center justify-center">
                                <button type="button" 
                                        onclick="deleteStrukturImage()"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Gambar
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-50 rounded-lg p-8 text-center border-2 border-dashed border-gray-200">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada gambar struktur organisasi</p>
                    </div>
                    @endif
                </div>
                
                <!-- Upload New Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ ($profiles['struktur_organisasi_image'] ?? false) ? 'Ganti Gambar:' : 'Upload Gambar:' }}
                    </label>
                    <div x-data="{ 
                        dragOver: false,
                        fileName: '',
                        preview: null,
                        handleFile(file) {
                            if (file && file.type.startsWith('image/')) {
                                this.fileName = file.name;
                                const reader = new FileReader();
                                reader.onload = (e) => this.preview = e.target.result;
                                reader.readAsDataURL(file);
                            }
                        }
                    }"
                         @dragover.prevent="dragOver = true"
                         @dragleave.prevent="dragOver = false"
                         @drop.prevent="dragOver = false; handleFile($event.dataTransfer.files[0]); $refs.fileInput.files = $event.dataTransfer.files"
                         :class="{ 'border-primary-500 bg-primary-50': dragOver }"
                         class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition-colors cursor-pointer">
                        
                        <input type="file" 
                               name="struktur_image" 
                               accept="image/jpeg,image/png,image/jpg"
                               class="hidden" 
                               id="struktur_image"
                               x-ref="fileInput"
                               @change="handleFile($event.target.files[0])">
                        
                        <label for="struktur_image" class="cursor-pointer">
                            <!-- Preview -->
                            <template x-if="preview">
                                <div class="mb-4">
                                    <img :src="preview" class="max-h-40 mx-auto rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600 mt-2" x-text="fileName"></p>
                                </div>
                            </template>
                            
                            <!-- Upload Icon -->
                            <template x-if="!preview">
                                <div>
                                    <svg class="w-10 h-10 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-gray-600 mb-1 text-sm">Klik atau drag & drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG (Maks. 2MB)</p>
                                </div>
                            </template>
                        </label>
                    </div>
                    @error('struktur_image')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Submit Button -->
    <div class="mt-6 flex justify-end">
        <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Semua Perubahan
        </button>
    </div>
</form>

@push('scripts')
<script>
function deleteStrukturImage() {
    if (confirm('Apakah Anda yakin ingin menghapus gambar struktur organisasi?')) {
        fetch('{{ route('admin.settings.profile.delete-struktur-image') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus gambar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}
</script>
@endpush
@endsection
