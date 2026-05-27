@extends('layouts.admin')

@section('title', 'Relasi Orang Tua - Siswa')
@section('icon', 'fas fa-people-roof')

@section('content')
<div class="max-w-6xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Relasi Orang Tua → Siswa</h1>
        <p class="text-gray-600 mt-1 text-sm">Pilih siswa yang dimonitor oleh masing-masing akun orang tua.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 font-semibold text-gray-900">Daftar Orang Tua</div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Orang Tua</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Siswa dimonitor</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($parents as $p)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $p->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $p->email }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.parent-students.update', $p) }}" class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                    @csrf
                                    @method('PUT')

                                    <select name="student_ids[]" multiple class="w-full min-w-[260px] border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                        @foreach($students as $s)
                                            <option value="{{ $s->id }}" @selected($p->children->contains('id', $s->id))>{{ $s->name }}</option>
                                        @endforeach
                                    </select>

                                    <button class="inline-flex justify-center items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg text-sm">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </form>

                                @if($p->children->count() > 0)
                                    <div class="text-xs text-gray-500 mt-2">Saat ini: {{ $p->children->pluck('name')->join(', ') }}</div>
                                @else
                                    <div class="text-xs text-gray-500 mt-2">Saat ini: —</div>
                                @endif
                            </td>
                            <td class="px-4 py-3"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $parents->links() }}
        </div>
    </div>
</div>
@endsection
