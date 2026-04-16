@extends('layouts.guru')
@section('title', 'Detail Presensi - ' . $session->classSubject->eClass->name . ' - ' . $session->classSubject->subject->name)
@section('icon', 'fas fa-clipboard-list')

@section('content')

<div class="mb-6">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-clipboard-list mr-1"></i> Guru / Presensi / Detail</p>
    <h1 class="text-2xl font-extrabold text-gray-900">{{ $session->classSubject->eClass->name }} — {{ $session->classSubject->subject->name }}</h1>
</div>

{{-- Session Info + Stats --}}
<div class="bg-white rounded-2xl border-2 border-gray-100 overflow-hidden mb-6">
    <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100">
        <div>
            <p class="font-bold text-gray-900"><i class="fas fa-calendar mr-2 text-gray-400"></i>{{ $session->attendance_date->format('l, d F Y') }}</p>
            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-clock mr-2"></i>Dibuka: {{ $session->opened_at }}</p>
        </div>
        <span class="self-start sm:self-auto text-xs font-semibold px-3 py-1.5 rounded-full
            {{ $session->isOpen() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($session->isClosed() ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-100 text-gray-500 border border-gray-200') }}">
            {{ $session->isOpen() ? '● Terbuka' : ($session->isClosed() ? '✓ Ditutup' : '✕ Dibatalkan') }}
        </span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-100">
        <div class="text-center py-4 px-3">
            <p class="text-2xl font-extrabold text-gray-900">{{ $session->records->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Siswa</p>
        </div>
        <div class="text-center py-4 px-3">
            <p class="text-2xl font-extrabold text-emerald-600">{{ $session->records->where('status','present')->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">Hadir</p>
        </div>
        <div class="text-center py-4 px-3">
            <p class="text-2xl font-extrabold text-red-500">{{ $session->records->where('status','absent')->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">Tidak Hadir</p>
        </div>
        <div class="text-center py-4 px-3">
            <p class="text-2xl font-extrabold text-blue-600">{{ $session->getAttendancePercentage() }}%</p>
            <p class="text-xs text-gray-400 mt-1">Kehadiran</p>
        </div>
    </div>
</div>

@if($session->isOpen())
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <form action="{{ route('guru.attendance.close', $session) }}" method="POST" class="attendance-form flex-1">
            @csrf
            <button type="button" onclick="confirmAttendanceAction(event,'close')"
                class="w-full inline-flex justify-center items-center gap-2 bg-orange-50 hover:bg-orange-500 text-orange-600 hover:text-white border border-orange-200 font-semibold py-2.5 px-4 rounded-xl text-sm transition">
                <i class="fas fa-door-closed text-xs"></i> Tutup Presensi
            </button>
        </form>
        <form action="{{ route('guru.attendance.cancel', $session) }}" method="POST" class="attendance-form flex-1">
            @csrf
            <button type="button" onclick="confirmAttendanceAction(event,'cancel')"
                class="w-full inline-flex justify-center items-center gap-2 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 font-semibold py-2.5 px-4 rounded-xl text-sm transition">
                <i class="fas fa-ban text-xs"></i> Batalkan Presensi
            </button>
        </form>
    </div>
@endif

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900"><i class="fas fa-users mr-2 text-gray-400"></i>Daftar Absensi Siswa</h2>
    </div>

    @if($session->records->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($session->records->sortBy('student.name') as $record)
                        @php
                            $statusMap = ['present'=>['bg-emerald-50 text-emerald-700 border-emerald-200','fa-check-circle','Hadir'],'absent'=>['bg-red-50 text-red-600 border-red-200','fa-times-circle','Tidak Hadir'],'late'=>['bg-yellow-50 text-yellow-700 border-yellow-200','fa-hourglass-end','Terlambat'],'excused'=>['bg-blue-50 text-blue-600 border-blue-200','fa-clipboard','Izin']];
                            [$sc,$ic,$sl] = $statusMap[strtolower($record->status)] ?? $statusMap['absent'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $record->student->name }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $sc }}">
                                    <i class="fas {{ $ic }} text-[10px]"></i> {{ $sl }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs">
                                {{ $record->checked_in_at ? $record->checked_in_at->format('H:i') : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <i class="fas fa-inbox text-gray-200 text-4xl mb-3"></i>
            <p class="text-gray-400 text-sm">Belum ada data presensi.</p>
        </div>
    @endif
</div>

<div class="mt-6">
    <a href="{{ route('guru.attendance.index') }}"
       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Daftar
    </a>
</div>

@endsection

@push('scripts')
<script>
function confirmAttendanceAction(event, action) {
    event.preventDefault();
    const form = event.target.closest('form');
    const configs = {
        close: ['Tutup presensi? Siswa tidak bisa absensi lagi.', 'Konfirmasi Tutup'],
        cancel: ['Batalkan presensi? Data akan dihapus.', 'Konfirmasi Batalkan']
    };
    showConfirmation(...configs[action], () => form.submit());
}
</script>
@endpush