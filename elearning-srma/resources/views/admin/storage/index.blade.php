@extends('layouts.admin')

@section('title', 'Manajemen Penyimpanan')
@section('page-title', 'Manajemen Penyimpanan')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Penyimpanan</h1>
        <p class="text-gray-600 text-sm mt-1">Pantau pemakaian storage dan lakukan pembersihan file yang tidak diperlukan.</p>
    </div>

    <!-- Summary cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 md:col-span-1">
            <div class="text-xs font-semibold text-gray-500 uppercase">Total (public)</div>
            <div class="text-xl font-bold text-gray-900 mt-1">{{ $summary['total']['human'] }}</div>
            <div class="text-xs text-gray-600 mt-1">{{ $summary['total']['files'] }} file</div>
        </div>

        @foreach($summary['by_folder'] as $folder => $data)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="text-xs font-semibold text-gray-500 uppercase">{{ $folder }}</div>
                <div class="text-lg font-bold text-gray-900 mt-1">{{ $data['human'] }}</div>
                <div class="text-xs text-gray-600 mt-1">{{ $data['files'] }} file</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cleanup panel -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900">Cleanup cepat</h2>
                <p class="text-xs text-gray-600 mt-1">Hapus file lama berdasarkan <span class="font-semibold">last modified</span>.</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.storage.cleanup') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Folder</label>
                        <select name="folder" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                            @foreach(['submissions','materials','assignments','banners'] as $f)
                                <option value="{{ $f }}" @selected(old('folder','submissions') === $f)>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Hapus yang lebih lama dari (hari)</label>
                        <input type="number" min="1" max="3650" name="days" value="{{ old('days', 180) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                        <p class="text-xs text-gray-500 mt-1">Contoh: 180 = 6 bulan</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Keep latest (opsional)</label>
                        <input type="number" min="0" name="keep_latest" value="{{ old('keep_latest') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Misal: 50">
                        <p class="text-xs text-gray-500 mt-1">Jika diisi, file terbaru sebanyak ini tidak akan dihapus.</p>
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="dry_run" value="1" @checked(old('dry_run', true))>
                        Simulasi saja (tidak benar-benar menghapus)
                    </label>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition">
                        Jalankan Cleanup
                    </button>
                </form>

                <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-xs text-amber-900">
                        <span class="font-bold">Catatan:</span>
                        Cleanup ini hanya menghapus file fisik pada disk <span class="font-semibold">public</span>. Ia tidak menghapus data pada database.
                        Untuk file yang masih direferensikan DB, sebaiknya hapus dari fitur masing-masing (Materi/Tugas/Nilai).
                    </p>
                </div>
            </div>
        </div>

        <!-- Largest files panel -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-gray-900">File terbesar</h2>
                    <p class="text-xs text-gray-600 mt-1">Pilih file lalu hapus untuk mengosongkan ruang.</p>
                </div>

                <form method="GET" action="{{ route('admin.storage.index') }}" class="flex items-center gap-2">
                    <select name="largest_folder" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        @foreach(['submissions','materials','assignments','banners'] as $f)
                            <option value="{{ $f }}" @selected($largestFolder === $f)>{{ $f }}</option>
                        @endforeach
                    </select>
                    <select name="largest_limit" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        @foreach([10,20,50,100] as $n)
                            <option value="{{ $n }}" @selected((int)$largestLimit === $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-semibold">Tampilkan</button>
                </form>
            </div>

            <form method="POST" action="{{ route('admin.storage.delete') }}">
                @csrf

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 w-12">
                                    <input type="checkbox" id="check-all">
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Path</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 w-32">Ukuran</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 w-44">Last modified</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($largestFiles as $f)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <input type="checkbox" name="paths[]" value="{{ $f['path'] }}" class="file-check">
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-semibold text-gray-900">{{ $f['path'] }}</div>
                                    </td>
                                    <td class="px-6 py-3 font-semibold text-gray-900">{{ $f['human'] }}</td>
                                    <td class="px-6 py-3 text-gray-700">
                                        @if($f['last_modified'])
                                            {{ \Carbon\Carbon::createFromTimestamp($f['last_modified'])->format('d M Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-600">Tidak ada file di folder ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-xs text-gray-600">
                        Hapus file hanya jika Anda yakin tidak diperlukan.
                    </div>
                    <button type="submit" onclick="return confirm('Yakin ingin menghapus file yang dipilih?')"
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-sm">
                        Hapus Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const checkAll = document.getElementById('check-all');
    const checks = () => Array.from(document.querySelectorAll('.file-check'));

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            checks().forEach(cb => cb.checked = checkAll.checked);
        });
    }
</script>
@endpush
