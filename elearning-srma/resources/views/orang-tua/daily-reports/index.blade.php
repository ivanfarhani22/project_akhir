@extends('layouts.orang-tua')

@section('title', 'Laporan Harian')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Harian Siswa</h1>
        <p class="text-gray-600">Rekap per kegiatan (jadwal) untuk tanggal yang dipilih.</p>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-xl p-4 mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:items-end">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Anak</label>
            <select name="student_id" class="w-full border border-gray-200 rounded-lg px-3 py-2">
                <option value="">-- pilih --</option>
                @foreach($children as $c)
                    <option value="{{ $c->id }}" @selected((int)$studentId === (int)$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="date" value="{{ $date ?? now()->toDateString() }}" class="w-full border border-gray-200 rounded-lg px-3 py-2" />
        </div>

        <button class="inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-4 py-2 rounded-lg">
            <i class="fas fa-search"></i> Tampilkan
        </button>
    </form>

    @if($studentId)
        <div class="flex justify-end mb-3">
            <a href="{{ route('orang-tua.daily-reports.pdf', ['student_id' => $studentId, 'date' => $date]) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 text-sm font-semibold px-4 py-2 rounded-lg">
                <i class="fas fa-file-pdf text-[#A41E35]"></i> Cetak PDF
            </a>
        </div>
    @endif

    @if(! $studentId)
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-800">Pilih anak untuk melihat laporan.</div>
    @else
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                <div class="font-semibold text-gray-900">Nama: {{ $student?->name }}</div>
                <div class="text-sm text-gray-600">Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</div>
            </div>

            @if(($rows ?? collect())->count() === 0)
                <div class="p-4 text-gray-600">Belum ada data untuk tanggal ini.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Presensi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($rows as $r)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $r['no'] }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $r['kegiatan'] }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $r['presensi'] ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $r['nilai'] ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $r['catatan'] ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
