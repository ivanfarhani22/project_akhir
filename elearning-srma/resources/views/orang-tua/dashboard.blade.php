@extends('layouts.orang-tua')

@section('title', 'Dashboard Orang Tua')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Dashboard Orang Tua</h1>
    <p class="text-gray-600 mb-6">Gunakan menu untuk melihat jurnal harian anak.</p>

    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <a href="{{ route('orang-tua.daily-reports.index') }}" class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-4 py-2 rounded-lg">
            <i class="fas fa-book"></i> Lihat Jurnal Harian
        </a>
    </div>
</div>
@endsection
