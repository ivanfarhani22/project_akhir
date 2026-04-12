@extends('layouts.guru')

@section('title', 'Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <p class="text-gray-600 text-sm mb-2">Kelola Tugas</p>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <i class="fas fa-tasks text-blue-500"></i>
                Daftar Tugas
            </h1>
        </div>
        <a href="{{ route('guru.assignments.create') }}" class="bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition whitespace-nowrap inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Buat Tugas
        </a>
    </div>

    <!-- FILTER/SHORTCUT -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900">Ringkasan</h2>
        </div>
        <div class="p-6">
            @if($classes->count() > 0)
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('guru.assignments.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-4 rounded text-sm transition inline-flex items-center gap-2">
                        <i class="fas fa-list"></i> Semua Tugas
                    </a>
                    @foreach($classes as $class)
                        <a href="{{ route('guru.assignments.index', ['class_id' => $class->id]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-1.5 px-4 rounded text-sm transition">
                            {{ $class->name }}
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 px-6">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
                    <p class="text-gray-600 text-base mb-4">Belum ada tugas</p>
                    <a href="{{ route('guru.assignments.create') }}" class="bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i> Buat Tugas Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- LIST -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 text-lg">Daftar Tugas</h2>
            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                Total: {{ $assignments->count() }}
            </span>
        </div>

        @if($assignments->isEmpty())
            <div class="text-center py-12 px-6">
                <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-gray-600 text-base mb-4">Belum ada tugas</p>
                <a href="{{ route('guru.assignments.create') }}" class="bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-flex items-center gap-2">
                    <i class="fas fa-plus"></i> Buat Tugas Pertama
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Kelas</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Judul Tugas</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Deadline</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-900">Pengumpulan</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($assignments as $assignment)
                            @php
                                $submissions = $assignment->submissions;
                                $totalSubmissions = $submissions->count();
                                $isDeadlinePassed = $assignment->deadline < now();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $assignment->eClass->name }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ Str::limit($assignment->title, 40) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-semibold {{ $isDeadlinePassed ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        <i class="fas {{ $isDeadlinePassed ? 'fa-times-circle' : 'fa-clock' }}"></i>
                                        {{ $assignment->deadline->format('d M Y H:i') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-file-upload"></i>
                                        {{ $totalSubmissions }}/{{ $assignment->eClass->students->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('guru.assignments.edit', $assignment) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1 px-2 rounded text-xs transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('guru.assignments.destroy', $assignment) }}" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1 px-2 rounded text-xs transition" onclick="confirmDelete(event, '{{ $assignment->title }}')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(
        `Apakah Anda yakin ingin menghapus tugas "${name}"?`,
        'Konfirmasi Penghapusan',
        function() {
            form.submit();
        }
    );
}
</script>
@endpush