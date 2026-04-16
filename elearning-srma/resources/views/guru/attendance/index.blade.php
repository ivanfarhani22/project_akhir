@extends('layouts.guru')
@section('title', 'Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-clipboard-list mr-1"></i> Guru / Presensi</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-clipboard-list text-[#A41E35] mr-2"></i>Presensi</h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700">{{ $class->name }}</strong>
        </span>
    </div>
    <a href="{{ route('guru.attendance.create', ['class_id' => $class->id]) }}"
       class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition whitespace-nowrap">
        <i class="fas fa-plus text-xs"></i> Buat Sesi Presensi
    </a>
</div>

@if(session('success'))
    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($sessions->count() > 0)
    <div class="space-y-4 mb-6">
        @foreach($sessions as $session)
            <div class="bg-white rounded-2xl border-2 border-gray-100 hover:border-gray-200 transition overflow-hidden">
                <div class="p-5">
                    <div class="flex justify-between items-start gap-3 mb-4">
                        <div>
                            <p class="font-bold text-gray-900">{{ $session->attendance_date->format('l, d F Y') }}</p>
                            <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-clock mr-1"></i>{{ $session->opened_at }}{{ $session->closed_at ? ' - '.$session->closed_at : '' }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0
                            {{ $session->isOpen() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($session->isClosed() ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-100 text-gray-500 border border-gray-200') }}">
                            {{ $session->isOpen() ? '● Terbuka' : ($session->isClosed() ? '✓ Ditutup' : '✕ Dibatalkan') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-4 gap-3 mb-4 p-3 bg-gray-50 rounded-xl">
                        <div class="text-center">
                            <p class="text-xl font-extrabold text-gray-900">{{ $session->records->count() }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Total</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-extrabold text-emerald-600">{{ $session->records->where('status','present')->count() }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Hadir</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-extrabold text-red-500">{{ $session->records->where('status','absent')->count() }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Absen</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-extrabold text-blue-600">{{ $session->getAttendancePercentage() }}%</p>
                            <p class="text-xs text-gray-400 mt-0.5">Kehadiran</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('guru.attendance.show', $session) }}"
                           class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 text-xs font-semibold py-2 px-4 rounded-lg transition">
                            <i class="fas fa-eye text-[10px]"></i> Lihat
                        </a>
                        @if($session->isOpen())
                            <form action="{{ route('guru.attendance.close', $session) }}" method="POST" class="attendance-form">
                                @csrf
                                <button type="button" onclick="confirmAttendanceClose(event)"
                                    class="inline-flex items-center gap-1.5 bg-orange-50 hover:bg-orange-500 text-orange-500 hover:text-white border border-orange-200 text-xs font-semibold py-2 px-4 rounded-lg transition">
                                    <i class="fas fa-door-closed text-[10px]"></i> Tutup
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($sessions->hasPages())
        <div class="flex justify-center mt-6">{{ $sessions->links() }}</div>
    @endif
@else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-clipboard-list text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm mb-4">Belum ada presensi untuk kelas ini.</p>
            <a href="{{ route('guru.attendance.create', ['class_id' => $class->id]) }}"
               class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                <i class="fas fa-plus text-xs"></i> Buka Presensi Sekarang
            </a>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
function confirmAttendanceClose(event) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation('Tutup presensi?', 'Konfirmasi Tutup Presensi', () => form.submit());
}
</script>
@endpush