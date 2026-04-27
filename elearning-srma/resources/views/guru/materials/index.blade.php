@extends('layouts.guru')

@section('title', 'Materi Pembelajaran')
@section('icon', 'fas fa-book')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-chalkboard-teacher mr-1"></i> Guru / Materi</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-book text-[#A41E35] mr-2"></i>Materi Pembelajaran</h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700">{{ $class->name }}</strong>
        </span>
    </div>
    <a href="{{ isset($classSubject) && $classSubject ? route('guru.materials.create', $classSubject) : route('guru.materials.index') }}"
       class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all whitespace-nowrap">
        <i class="fas fa-plus text-xs"></i> Upload Materi
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Daftar Materi</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $materials->count() }} Materi</span>
    </div>

    <div class="p-6">
        @if($materials->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-folder-open text-3xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-sm mb-4">Belum ada materi yang diupload.</p>
                <a href="{{ isset($classSubject) && $classSubject ? route('guru.materials.create', $classSubject) : route('guru.materials.index') }}"
                   class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md transition-all">
                    <i class="fas fa-plus text-xs"></i> Upload Materi Pertama
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($materials as $material)
                    @php
                        $ext = strtolower($material->file_type);
                        $iconColors = ['pdf' => 'bg-red-50 text-red-600', 'doc' => 'bg-blue-50 text-blue-600', 'docx' => 'bg-blue-50 text-blue-600', 'ppt' => 'bg-orange-50 text-orange-600', 'pptx' => 'bg-orange-50 text-orange-600', 'xls' => 'bg-green-50 text-green-600', 'xlsx' => 'bg-green-50 text-green-600'];
                        $iconColor = $iconColors[$ext] ?? 'bg-gray-100 text-gray-500';
                    @endphp

                    <div class="group flex flex-col rounded-2xl border-2 border-gray-100 hover:border-[#A41E35] hover:shadow-lg transition-all duration-200 overflow-hidden bg-white">
                        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>

                        <a href="{{ route('guru.materials.show', $material) }}" class="flex items-start gap-3 p-4 flex-1">
                            <div class="w-11 h-11 rounded-xl {{ $iconColor }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                {{ strtoupper($ext) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 text-sm truncate">{{ Str::limit($material->title, 32) }}</h3>
                                <span class="inline-block text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full mt-0.5">v{{ $material->version }}</span>
                                @if($material->description)
                                    <p class="text-xs text-gray-500 mt-1.5 line-clamp-2">{{ $material->description }}</p>
                                @endif
                                <div class="flex justify-between items-center text-xs text-gray-400 mt-3 pt-2.5 border-t border-gray-100">
                                    <span><i class="fas fa-calendar-alt mr-1"></i>{{ $material->created_at->format('d M Y') }}</span>
                                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Dibagikan</span>
                                </div>
                            </div>
                        </a>

                        <div class="flex gap-2 px-4 pb-4" onclick="event.stopPropagation();">
                            <a href="{{ route('guru.materials.edit', $material) }}"
                               class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white border border-blue-200 py-2 rounded-lg transition-all">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('guru.materials.destroy', $material) }}" class="flex-1 delete-form">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, '{{ $material->title }}')"
                                        class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white border border-red-200 py-2 rounded-lg transition-all">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="mt-6">
    <a href="{{ url()->previous() ?? route('guru.dashboard') }}"
       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    event.stopPropagation();
    const form = event.target.closest('form');
    showConfirmation(`Apakah Anda yakin ingin menghapus materi "${name}"?`, 'Konfirmasi Penghapusan', () => form.submit());
}
</script>
@endpush