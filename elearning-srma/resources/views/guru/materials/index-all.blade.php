@extends('layouts.guru')

@section('title', 'Materi Pembelajaran')
@section('icon', 'fas fa-book')

@section('content')
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-book text-amber-500"></i>
                Materi Pembelajaran
            </h1>
            <p class="text-gray-600 text-sm">Kelola semua materi pembelajaran Anda</p>
        </div>
        <a href="{{ route('guru.materials.create') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-6 rounded-lg text-sm transition whitespace-nowrap inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Upload Materi
        </a>
    </div>

    <!-- CLASS FILTER -->
    @if($classes->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('guru.materials.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-1.5 px-4 rounded text-sm transition inline-flex items-center gap-2">
                    <i class="fas fa-list"></i> Semua Materi
                </a>
                @foreach($classes as $class)
                    <a href="{{ route('guru.materials.index', ['class_id' => $class->id]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-1.5 px-4 rounded text-sm transition">
                        {{ $class->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- MATERIALS SECTION -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 text-lg">Daftar Materi</h2>
            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                Total: {{ $materials->count() }}
            </span>
        </div>

        <div class="p-6">
            @if($materials->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
                    <p class="text-gray-600 text-base mb-4">Belum ada materi</p>
                    <a href="{{ route('guru.materials.create') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i> Upload Materi Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($materials as $material)
                        <div class="bg-white border border-gray-100 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 text-sm">{{ Str::limit($material->title, 30) }}</h3>
                                        <p class="text-xs text-gray-600 mt-1">
                                            <i class="fas fa-folder mr-1"></i> {{ $material->eClass->name }}
                                        </p>
                                    </div>
                                    <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded whitespace-nowrap">v{{ $material->version }}</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-700 text-sm mb-4 line-clamp-2">
                                    {{ $material->description ?? 'Tidak ada deskripsi' }}
                                </p>
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                    <span class="text-xs text-gray-600">
                                        <i class="fas fa-file mr-1"></i> {{ strtoupper($material->file_type) }}
                                    </span>
                                    <div class="flex gap-2">
                                        <a href="{{ route('guru.materials.edit', $material) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded text-xs transition">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('guru.materials.destroy', $material) }}" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-medium py-1 px-2 rounded text-xs transition" onclick="confirmDelete(event, '{{ $material->title }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(
        `Apakah Anda yakin ingin menghapus materi "${name}"?`,
        'Konfirmasi Penghapusan',
        function() {
            form.submit();
        }
    );
}
</script>
@endpush