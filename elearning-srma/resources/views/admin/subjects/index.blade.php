@extends('layouts.admin')
@section('title', 'Kelola Mata Pelajaran')
@section('icon', 'book')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Admin / Mata Pelajaran</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-book text-[#A41E35] mr-2"></i>Mata Pelajaran</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola semua mata pelajaran di sekolah.</p>
    </div>
    <a href="{{ route('admin.subjects.create') }}"
       class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-md hover:shadow-lg whitespace-nowrap">
        <i class="fas fa-plus text-xs"></i> Tambah Mata Pelajaran
    </a>
</div>

{{-- FILTER --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
    <div class="p-5">
        <form method="GET" action="{{ route('admin.subjects.index') }}"
              class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama, kode, atau deskripsi..."
                        class="w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                </div>
            </div>
            <div class="sm:w-52">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kategori</label>
                <select name="category"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                    <option value="">Semua Kategori</option>
                    <option value="academic"     @selected(request('category') === 'academic')>Akademik</option>
                    <option value="non_academic" @selected(request('category') === 'non_academic')>Non-Akademik (Asrama)</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition">
                    <i class="fas fa-search text-xs"></i> Cari
                </button>
                @if(request('search') || request('category'))
                    <a href="{{ route('admin.subjects.index') }}"
                       class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition">
                        <i class="fas fa-times text-xs"></i>
                    </a>
                @endif
            </div>
        </form>

        @if(request('search') || request('category'))
            <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-gray-400">Filter aktif:</span>
                @if(request('search'))
                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                        <i class="fas fa-search text-[10px]"></i> "{{ request('search') }}"
                    </span>
                @endif
                @if(request('category'))
                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                        <i class="fas fa-filter text-[10px]"></i>
                        {{ request('category') === 'academic' ? 'Akademik' : 'Non-Akademik' }}
                    </span>
                @endif
                <span class="text-xs text-gray-400 ml-auto">
                    {{ $subjects->count() }} dari {{ $subjects->total() }} mata pelajaran
                </span>
            </div>
        @endif
    </div>
</div>

{{-- TABEL --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Daftar Mata Pelajaran</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $subjects->total() }}</span>
    </div>

    @if($subjects->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-book text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-700 font-semibold text-sm mb-1">
                @if(request('search') || request('category'))
                    Tidak ada hasil yang cocok
                @else
                    Belum ada mata pelajaran
                @endif
            </p>
            <p class="text-xs text-gray-400 mb-4">
                @if(request('search') || request('category'))
                    Coba ubah kata kunci atau <a href="{{ route('admin.subjects.index') }}" class="text-[#A41E35] underline">reset filter</a>.
                @else
                    Mulai dengan <a href="{{ route('admin.subjects.create') }}" class="text-[#A41E35] underline">membuat mata pelajaran baru</a>.
                @endif
            </p>
        </div>
    @else
        {{-- TABEL DESKTOP --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mata Pelajaran</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($subjects as $subject)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0
                                        {{ $subject->category === 'non_academic' ? 'bg-purple-50' : 'bg-red-50' }}">
                                        <i class="fas {{ $subject->category === 'non_academic' ? 'fa-building text-purple-500' : 'fa-book text-[#A41E35]' }} text-xs"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $subject->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-full">
                                    {{ $subject->code }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($subject->category === 'non_academic')
                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 border border-purple-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fas fa-building text-[10px]"></i> Non-Akademik
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fas fa-graduation-cap text-[10px]"></i> Akademik
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 max-w-xs">
                                <p class="truncate">{{ $subject->description ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.subjects.edit', $subject) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs transition">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete(event, '{{ $subject->name }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-600 text-red-500 hover:text-white border border-red-200 rounded-lg text-xs transition">
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

        {{-- CARD MOBILE --}}
        <div class="md:hidden divide-y divide-gray-100">
            @foreach($subjects as $subject)
                <div class="p-4 flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                            {{ $subject->category === 'non_academic' ? 'bg-purple-50' : 'bg-red-50' }}">
                            <i class="fas {{ $subject->category === 'non_academic' ? 'fa-building text-purple-500' : 'fa-book text-[#A41E35]' }} text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-gray-900 text-sm truncate">{{ $subject->name }}</p>
                            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                <span class="text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">
                                    {{ $subject->code }}
                                </span>
                                @if($subject->category === 'non_academic')
                                    <span class="text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded-full">
                                        Non-Akademik
                                    </span>
                                @else
                                    <span class="text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full">
                                        Akademik
                                    </span>
                                @endif
                            </div>
                            @if($subject->description)
                                <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $subject->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <a href="{{ route('admin.subjects.edit', $subject) }}"
                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs transition">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete(event, '{{ $subject->name }}')"
                                class="inline-flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-600 text-red-500 hover:text-white border border-red-200 rounded-lg text-xs transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($subjects->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $subjects->links() }}
            </div>
        @endif
    @endif
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(
        `Apakah Anda yakin ingin menghapus mata pelajaran "${name}"? Aksi ini tidak dapat diubah.`,
        'Konfirmasi Penghapusan',
        () => form.submit()
    );
}
</script>
@endpush