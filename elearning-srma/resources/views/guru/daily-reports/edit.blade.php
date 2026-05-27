@extends('layouts.guru')
@section('title', 'Edit Jurnal Harian')
@section('icon', 'fas fa-pen-to-square')

@section('content')
<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Guru / Jurnal Harian / Edit</p>
    <h1 class="text-2xl font-extrabold text-gray-900">Edit Jurnal</h1>
    <p class="text-sm text-gray-500 mt-1">{{ $dailyReport->student?->name }} — {{ $dailyReport->report_date->format('d/m/Y') }}</p>
</div>

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Form Edit</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('guru.daily-reports.update', $dailyReport) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan</label>
                    <textarea name="notes" rows="5" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none">{{ old('notes', $dailyReport->notes) }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('guru.daily-reports.show', $dailyReport) }}" class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Batal
                    </a>
                    <button type="submit" class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
