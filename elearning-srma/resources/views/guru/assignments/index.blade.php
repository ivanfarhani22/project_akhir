@extends('layouts.guru')
@section('title', 'Tugas')
@section('icon', 'fas fa-tasks')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-tasks mr-1"></i> Guru / Tugas</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-tasks text-[#A41E35] mr-2"></i>Tugas</h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700">{{ $class->name }}</strong>
        </span>
    </div>
    <a href="{{ route('guru.assignments.create', ['class_id' => $class->id]) }}"
       class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition whitespace-nowrap">
        <i class="fas fa-plus text-xs"></i> Buat Tugas
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Daftar Tugas</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $assignments->count() }} Tugas</span>
    </div>

    @if($assignments->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-tasks text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm mb-4">Belum ada tugas untuk kelas ini.</p>
            <a href="{{ route('guru.assignments.create', ['class_id' => $class->id]) }}"
               class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                <i class="fas fa-plus text-xs"></i> Buat Tugas Pertama
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul Tugas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Submit</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($assignments as $assignment)
                        @php
                            $submitted = $assignment->submissions()->whereNotNull('submitted_at')->count();
                            $total = $class->students->count();
                            $pct = $total > 0 ? round(($submitted/$total)*100) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-900">{{ Str::limit($assignment->title, 40) }}</p>
                                @if($assignment->file_path)<p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-paperclip mr-1"></i>File tersedia</p>@endif
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-800 text-xs">{{ $assignment->deadline->format('d M Y') }}</p>
                                <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                    {{ $assignment->deadline->isPast() ? 'bg-red-50 text-red-600 border border-red-200' : ($assignment->deadline->diffInDays() <= 2 ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : 'bg-blue-50 text-blue-600 border border-blue-200') }}">
                                    {{ $assignment->deadline->isPast() ? 'Lewat' : ($assignment->deadline->diffInDays() <= 2 ? 'Segera' : 'Aktif') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <p class="text-xl font-extrabold text-blue-600">{{ $submitted }}</p>
                                <p class="text-xs text-gray-400">dari {{ $total }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium min-w-max">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('guru.assignments.show', $assignment) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs transition">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('guru.assignments.edit', $assignment) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-gray-50 hover:bg-gray-600 text-gray-600 hover:text-white border border-gray-200 rounded-lg text-xs transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('guru.assignments.destroy', $assignment) }}" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete(event, '{{ $assignment->title }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 rounded-lg text-xs transition">
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

<div class="mt-6">
    <a href="{{ url()->previous() ?? route('guru.dashboard') }}"
       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition">
        <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Apakah Anda yakin ingin menghapus tugas "${name}"?`, 'Konfirmasi Penghapusan', () => form.submit());
}
</script>
@endpush