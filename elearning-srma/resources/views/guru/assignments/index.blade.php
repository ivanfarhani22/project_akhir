@extends('layouts.guru')

@section('title', 'Manajemen Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <p class="text-gray-600 text-sm mb-2">Kelola Tugas</p>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-tasks text-blue-500"></i>
                Manajemen Tugas Pembelajaran
            </h1>
            <p class="text-gray-600 text-sm">Kelas: <strong>{{ $class->name }}</strong></p>
        </div>
        <a href="{{ route('guru.assignments.create', ['class_id' => $class->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm transition whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i> Buat Tugas
        </a>
    </div>

    <!-- ASSIGNMENTS SECTION -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 text-lg">Daftar Tugas</h2>
            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                Total: {{ $assignments->count() }}
            </span>
        </div>

        <div class="p-6">
            @if($assignments->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
                    <p class="text-gray-600 text-base mb-4">Belum ada tugas</p>
                    <a href="{{ route('guru.assignments.create', ['class_id' => $class->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-block">
                        <i class="fas fa-plus mr-2"></i> Buat Tugas Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left font-semibold text-gray-900">Judul Tugas</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-900">Deadline</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-900">Submission</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-900">Progress</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($assignments as $assignment)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <strong class="text-gray-900">{{ Str::limit($assignment->title, 40) }}</strong>
                                        @if($assignment->file_path)
                                            <div class="mt-1 text-xs text-gray-600">
                                                <i class="fas fa-paperclip"></i> File tersedia
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-gray-900">{{ $assignment->deadline->format('d M Y') }}</span>
                                        <div class="mt-2">
                                            @if($assignment->deadline->isPast())
                                                <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Sudah Lewat</span>
                                            @elseif($assignment->deadline->diffInDays() <= 2)
                                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Segera</span>
                                            @else
                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">Aktif</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="text-2xl font-bold text-blue-600">
                                            {{ $assignment->submissions()->whereNotNull('submitted_at')->count() }}
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">
                                            dari {{ $class->students->count() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $submitted = $assignment->submissions()->whereNotNull('submitted_at')->count();
                                            $total = $class->students->count();
                                            $percentage = $total > 0 ? round(($submitted / $total) * 100) : 0;
                                        @endphp
                                        <div class="flex items-center gap-3 justify-center">
                                            <div class="flex-1">
                                                <div class="w-full h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-green-500 to-green-600" style="width: {{ $percentage }}%;"></div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-600 font-medium min-w-max">{{ $percentage }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2 justify-center">
                                            <a href="{{ route('guru.assignments.show', $assignment) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded text-xs transition">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('guru.assignments.edit', $assignment) }}" class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-1.5 px-3 rounded text-xs transition">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('guru.assignments.destroy', $assignment) }}" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-medium py-1.5 px-3 rounded text-xs transition"
                                                    onclick="confirmDelete(event, '{{ $assignment->title }}')">
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
    </div>

    <!-- BACK BUTTON -->
    <div class="mt-8">
        <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-6 rounded-lg text-sm transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
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