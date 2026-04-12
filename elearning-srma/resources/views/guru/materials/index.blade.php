@extends('layouts.guru')

@section('title', 'Materi Pembelajaran')
@section('icon', 'fas fa-book')

@section('content')
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <p class="text-gray-600 text-sm mb-2">Kelola Materi</p>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-book text-amber-500"></i>
                Materi Pembelajaran
            </h1>
            <p class="text-gray-600 text-sm">Kelas: <strong>{{ $class->name }}</strong></p>
        </div>
        <a href="{{ route('guru.materials.create', ['class_id' => $class->id]) }}" class="bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i> Upload Materi
        </a>
    </div>

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
                    <a href="{{ route('guru.materials.create', ['class_id' => $class->id]) }}" class="bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-block">
                        <i class="fas fa-plus mr-2"></i> Upload Materi Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($materials as $material)
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-sm">{{ Str::limit($material->title, 30) }}</h3>
                                        <p class="text-xs text-gray-600 mt-1">
                                            <i class="fas fa-file"></i> {{ strtoupper($material->file_type) }}
                                        </p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded whitespace-nowrap">
                                        v{{ $material->version }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4">
                                @if($material->description)
                                    <p class="text-sm text-gray-700 mb-4 line-clamp-3">
                                        {{ Str::limit($material->description, 80) }}
                                    </p>
                                @endif

                                <div class="flex justify-between items-center text-xs text-gray-600 py-3 border-t border-b border-gray-200 my-3">
                                    <span><i class="fas fa-calendar mr-1"></i> {{ $material->created_at->format('d M Y') }}</span>
                                    <span><i class="fas fa-eye mr-1"></i> Dibagikan</span>
                                </div>

                                <div class="flex gap-2 mt-4">
                                    <a href="{{ route('guru.materials.edit', $material) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-3 rounded text-xs transition text-center">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('guru.materials.destroy', $material) }}" class="flex-1 delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded text-xs transition"
                                            onclick="confirmDelete(event, '{{ $material->title }}')">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- BACK BUTTON -->
    <div class="mt-8">
        <a href="{{ url()->previous() ?? route('guru.dashboard') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-6 rounded-lg text-sm transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
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