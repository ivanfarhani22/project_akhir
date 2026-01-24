@extends('layouts.public')

@section('title', 'Struktur Organisasi - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Profil</span></li>
                <li><span>/</span></li>
                <li><span class="text-white">Struktur Organisasi</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Struktur Organisasi</h1>
    </div>
</section>

<!-- Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @include('partials.profile-sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Struktur Organisasi</h2>
                    
                    @if($strukturImage)
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $strukturImage) }}" 
                             alt="Struktur Organisasi SRMA 25 Lamongan" 
                             class="max-w-full h-auto rounded-lg shadow-md mx-auto cursor-pointer hover:shadow-lg transition-shadow"
                             onclick="openImageModal(this.src)">
                        <p class="text-sm text-gray-500 mt-4">Klik gambar untuk memperbesar</p>
                    </div>
                    @else
                    <div class="bg-gray-50 rounded-lg p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Gambar Struktur Organisasi</h3>
                        <p class="text-gray-500">Gambar struktur organisasi belum tersedia.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90" onclick="closeImageModal()">
    <button class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors" onclick="closeImageModal()">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <img id="modalImage" src="" alt="" class="max-w-[95vw] max-h-[95vh] object-contain" onclick="event.stopPropagation()">
</div>

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
    document.body.style.overflow = '';
}

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImageModal();
});
</script>
@endsection
