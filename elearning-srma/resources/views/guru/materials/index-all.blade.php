@extends('layouts.guru')

@section('title', 'Materi Pembelajaran')
@section('icon', 'fas fa-book')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-chalkboard-teacher mr-1"></i> Guru / Materi
            </p>
            <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-book text-[#A41E35] mr-2"></i>Materi Pembelajaran
            </h1>
            <p class="text-sm text-gray-500 mt-1">Silakan pilih kelas & mata pelajaran untuk melihat atau mengupload materi.
            </p>
        </div>
        <a href="{{ route('guru.materials.index') }}"
           class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all whitespace-nowrap">
            <i class="fas fa-layer-group text-xs"></i> Pilih Kelas / Mapel
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Daftar Materi</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($materials as $material)
                <div class="bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="font-semibold text-gray-800">{{ $material->title }}</h3>
                    <p class="text-gray-600 text-sm mt-1">{{ $material->description }}</p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <a href="{{ route('guru.materials.show', $material) }}"
                           class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                            <i class="fas fa-eye"></i> Lihat Materi
                        </a>
                        <a href="{{ route('guru.materials.edit', $material) }}"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                            <i class="fas fa-edit"></i> Edit Materi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $materials->links() }}
        </div>
    </div>
@endsection