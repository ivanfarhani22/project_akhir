@extends('layouts.guru')

@section('title', 'Halaman Tidak Ditemukan')
@section('icon', 'fas fa-triangle-exclamation')

@section('content')
    <div class="mb-8">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">
            <i class="fas fa-triangle-exclamation mr-1"></i> Guru / Error
        </p>
        <h1 class="text-2xl font-extrabold text-gray-900">
            <i class="fas fa-triangle-exclamation text-[#A41E35] mr-2"></i>Halaman Tidak Ditemukan
        </h1>
        <p class="text-sm text-gray-500 mt-1">Maaf, halaman atau data yang Anda cari tidak tersedia.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="p-6 sm:p-8">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-xl font-extrabold text-gray-400">404</span>
                </div>

                <p class="text-gray-900 font-bold">Data tidak ditemukan</p>
                <p class="text-sm text-gray-500 mt-1 max-w-xl">
                    Link yang Anda buka mungkin sudah tidak berlaku, parameter URL tidak valid, atau data aktivitas harian belum tersedia.
                </p>

                @if(!empty($exception?->getMessage()))
                    <div class="mt-4 w-full max-w-2xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-circle-info mt-0.5"></i>
                            <div>
                                <p class="font-semibold">Detail</p>
                                <p class="mt-0.5">{{ $exception->getMessage() }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ url()->previous() }}"
                       class="inline-flex justify-center items-center gap-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl text-sm transition shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>

                    <a href="{{ route('guru.daily-activities.create') }}"
                       class="inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-6 py-3 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-clipboard-check text-xs"></i> Aktivitas Harian
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
