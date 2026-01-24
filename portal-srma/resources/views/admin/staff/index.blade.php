@extends('layouts.admin')

@section('title', 'Kelola Tenaga Kependidikan')
@section('page-title', 'Kelola Tenaga Kependidikan')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Data Tenaga Kependidikan</h2>
                <p class="text-sm text-gray-500">Kelola data tenaga kependidikan sekolah</p>
            </div>
            <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tenaga Kependidikan
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($staff as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-full object-cover">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $item->name }}</div>
                        @if($item->nip)
                        <div class="text-sm text-gray-500">NIP: {{ $item->nip }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->position }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.staff.edit', $item) }}" class="p-2 text-blue-500 hover:text-blue-700" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.staff.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:text-red-700" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        Belum ada data tenaga kependidikan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($staff->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $staff->links() }}
    </div>
    @endif
</div>
@endsection
