@extends('layouts.admin')

@section('title', 'Kelola Siswa - ' . $class->name)
@section('icon', 'fas fa-users')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-users"></i>
                </span>
                Kelola Siswa
            </h1>
            <p class="text-gray-600 mt-2">Kelas: <strong>{{ $class->name }}</strong></p>
        </div>
        <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center gap-2 bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <div class="flex">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    @if(session('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded">
            <div class="flex">
                <i class="fas fa-info-circle text-yellow-600 mr-3"></i>
                <p class="text-yellow-800">{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Section 1: Tambah Siswa -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-plus-circle"></i>
                </span>
                Tambah Siswa Baru
            </h3>

            <form method="POST" action="{{ route('admin.classes.students.store', $class) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Pilih Siswa:</label>
                    <select name="student_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($allStudents as $student)
                            @if(!in_array($student->id, $enrolledStudentIds))
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} ({{ $student->email }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-green-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-green-600 transition">
                    <i class="fas fa-plus"></i> Tambah Siswa
                </button>
            </form>
        </div>

        <!-- Section 2: Info Kelas -->
        <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-info-circle"></i>
                </span>
                Info Kelas
            </h3>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Nama Kelas</p>
                    <p class="text-gray-900">{{ $class->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Total Siswa</p>
                    <p class="text-2xl font-bold text-red-600">{{ $students->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">
                <i class="fas fa-list mr-2"></i>Daftar Siswa ({{ $students->total() }})
            </h3>
        </div>

        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b-2 border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">No.</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <strong class="text-gray-900">{{ $student->name }}</strong>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $student->email }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form method="POST" action="{{ route('admin.classes.students.destroy', [$class, $student]) }}" 
                                        style="display: inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(event, '{{ $student->name }}')" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg font-semibold text-xs hover:bg-red-700 transition">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-center">
                {{ $students->links() }}
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4 inline-block"></i>
                <p class="text-gray-600">Belum ada siswa di kelas ini. Silakan tambahkan siswa terlebih dahulu!</p>
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
    showConfirmation(`Yakin ingin menghapus "${name}" dari kelas ini?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}
</script>
@endpush
