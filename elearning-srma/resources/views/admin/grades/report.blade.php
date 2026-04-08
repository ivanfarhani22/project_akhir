@extends('layouts.admin')

@section('title', 'Laporan Nilai')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 mb-6">
        <i class="fas fa-chart-bar text-red-600"></i>
        <span class="text-gray-600">Admin</span>
        <span class="text-gray-400">/</span>
        <span class="font-semibold text-gray-800">Laporan Nilai</span>
    </div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-black text-gray-800 mb-2">Laporan Nilai</h1>
            <p class="text-gray-600">Analisis komprehensif data nilai siswa</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('admin.grades.index') }}" class="inline-flex items-center gap-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-3 rounded-lg transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.grades.report', array_merge(request()->query(), ['export' => 'csv'])) }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.grades.report') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-list text-red-600 mr-2"></i>Tipe Laporan
                </label>
                <select name="type" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none" onchange="this.form.submit()">
                    <option value="summary" {{ request('type') === 'summary' ? 'selected' : '' }}>Ringkasan</option>
                    <option value="detailed" {{ request('type') === 'detailed' ? 'selected' : '' }}>Detail</option>
                    <option value="comparative" {{ request('type') === 'comparative' ? 'selected' : '' }}>Perbandingan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-door-open text-red-600 mr-2"></i>Kelas
                </label>
                <select name="class_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classes ?? [] as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar text-red-600 mr-2"></i>Dari Tanggal
                </label>
                <input type="date" name="from" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none" value="{{ request('from') }}" onchange="this.form.submit()">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar text-red-600 mr-2"></i>Sampai Tanggal
                </label>
                <input type="date" name="to" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none" value="{{ request('to') }}" onchange="this.form.submit()">
            </div>
        </form>
    </div>

    <!-- Report Content -->
    @if($type === 'summary')
        @include('admin.grades.report-summary')
    @elseif($type === 'detailed')
        @include('admin.grades.report-detailed')
    @elseif($type === 'comparative')
        @include('admin.grades.report-comparative')
    @endif
</div>

@endsection
