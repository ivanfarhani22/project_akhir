@extends('layouts.public')

@section('title', 'Persebaran Siswa - SRMA 25 Lamongan')

@push('styles')
<style>
    .sortable-header {
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
    }
    .sortable-header:hover {
        background-color: #d1d5db;
    }
    .sort-icon {
        display: inline-flex;
        flex-direction: column;
        margin-left: 6px;
        font-size: 8px;
        line-height: 1;
        vertical-align: middle;
    }
    .sort-icon span {
        opacity: 0.3;
        transition: opacity 0.2s, color 0.2s;
    }
    .sort-icon span.active {
        opacity: 1;
        color: #059669;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex flex-wrap items-center gap-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Profil</span></li>
                <li><span>/</span></li>
                <li><span class="text-white">Persebaran Siswa</span></li>
            </ol>
        </nav>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white">Persebaran Siswa</h1>
    </div>
</section>

<!-- Content -->
<section class="py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-6 lg:gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @include('partials.profile-sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Persebaran Siswa Berdasarkan Wilayah</h2>
                        <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs sm:text-sm font-medium w-fit">
                            Tahun Ajaran {{ $currentYear }}
                        </span>
                    </div>
                    
                    @if($distributions->count() > 0)
                        <!-- Chart -->
                        <div class="mb-6 sm:mb-8" style="height: 250px;">
                            <canvas id="distributionChart"></canvas>
                        </div>
                        
                        <!-- Info -->
                        <!-- <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Klik header kolom <strong>Kecamatan/Wilayah</strong> atau <strong>Jumlah Siswa</strong> untuk mengurutkan. Grafik akan otomatis menyesuaikan.
                            </p>
                        </div> -->
                        
                        <!-- Data Table -->
                        <div class="overflow-x-auto -mx-4 sm:mx-0">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden sm:rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200" id="distributionTable">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 w-10 sm:w-16">No</th>
                                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 sortable-header" data-sort="district" data-order="none">
                                                    <div class="flex items-center">
                                                        <span class="hidden sm:inline">Kecamatan/Wilayah</span>
                                                        <span class="sm:hidden">Wilayah</span>
                                                        <span class="sort-icon">
                                                            <span class="asc">▲</span>
                                                            <span class="desc">▼</span>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 sortable-header" data-sort="count" data-order="desc">
                                                    <div class="flex items-center justify-center">
                                                        <span class="hidden sm:inline">Jumlah Siswa</span>
                                                        <span class="sm:hidden">Jml</span>
                                                        <span class="sort-icon">
                                                            <span class="asc">▲</span>
                                                            <span class="desc active">▼</span>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700">
                                                    <span class="hidden sm:inline">Persentase</span>
                                                    <span class="sm:hidden">%</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white" id="tableBody">
                                            <!-- Data will be populated by JavaScript -->
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-gray-100 font-semibold">
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-800" colspan="2">Total</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-gray-800" id="totalCount">0</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-gray-800">100%</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                            <p class="text-gray-500">Data persebaran siswa untuk tahun ajaran ini belum tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@if($distributions->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Raw data from server
    const rawData = @json($distributions->map(function($d) {
        return ['district' => $d->district, 'count' => $d->student_count];
    })->values());
    
    // Calculate total
    const totalStudents = rawData.reduce((sum, item) => sum + item.count, 0);
    document.getElementById('totalCount').textContent = totalStudents;
    
    // Colors palette
    const colors = [
        '#ef4444', '#f97316', '#eab308', '#84cc16', '#22c55e',
        '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1',
        '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e',
        '#64748b', '#78716c', '#71717a', '#737373', '#525252'
    ];
    
    // Current sort state
    let currentSort = { field: 'count', order: 'desc' };
    let sortedData = [...rawData];
    
    // Initialize chart
    const ctx = document.getElementById('distributionChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Jumlah Siswa',
                data: [],
                backgroundColor: [],
                borderColor: [],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 400
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const percentage = ((context.raw / totalStudents) * 100).toFixed(1);
                            return `${context.raw} siswa (${percentage}%)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Siswa'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Kecamatan/Wilayah'
                    }
                }
            }
        }
    });
    
    // Sort function
    function sortData(field, order) {
        sortedData = [...rawData].sort((a, b) => {
            let valA, valB;
            
            if (field === 'district') {
                valA = a.district.toLowerCase();
                valB = b.district.toLowerCase();
                if (order === 'asc') {
                    return valA.localeCompare(valB);
                } else {
                    return valB.localeCompare(valA);
                }
            } else {
                valA = a.count;
                valB = b.count;
                if (order === 'asc') {
                    return valA - valB;
                } else {
                    return valB - valA;
                }
            }
        });
        
        currentSort = { field, order };
        updateTable();
        updateChart();
        updateSortIcons();
    }
    
    // Update table
    function updateTable() {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';
        
        sortedData.forEach((item, index) => {
            const percentage = totalStudents > 0 ? ((item.count / totalStudents) * 100).toFixed(1) : 0;
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors';
            row.innerHTML = `
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-600">${index + 1}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-medium text-gray-800">${item.district}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-gray-800">${item.count}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center">
                    <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-primary-100 text-primary-700 rounded text-xs font-medium">
                        ${percentage}%
                    </span>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
    
    // Update chart
    function updateChart() {
        const labels = sortedData.map(item => item.district);
        const data = sortedData.map(item => item.count);
        const bgColors = sortedData.map((_, i) => colors[i % colors.length] + 'CC');
        const borderColors = sortedData.map((_, i) => colors[i % colors.length]);
        
        chart.data.labels = labels;
        chart.data.datasets[0].data = data;
        chart.data.datasets[0].backgroundColor = bgColors;
        chart.data.datasets[0].borderColor = borderColors;
        chart.update('active');
    }
    
    // Update sort icons
    function updateSortIcons() {
        document.querySelectorAll('.sortable-header').forEach(header => {
            const field = header.dataset.sort;
            const ascIcon = header.querySelector('.asc');
            const descIcon = header.querySelector('.desc');
            
            ascIcon.classList.remove('active');
            descIcon.classList.remove('active');
            
            if (currentSort.field === field) {
                header.dataset.order = currentSort.order;
                if (currentSort.order === 'asc') {
                    ascIcon.classList.add('active');
                } else {
                    descIcon.classList.add('active');
                }
            } else {
                header.dataset.order = 'none';
            }
        });
    }
    
    // Click handlers for sortable headers
    document.querySelectorAll('.sortable-header').forEach(header => {
        header.addEventListener('click', function() {
            const field = this.dataset.sort;
            let order = this.dataset.order;
            
            // Toggle order
            if (order === 'none' || order === 'desc') {
                order = 'asc';
            } else {
                order = 'desc';
            }
            
            sortData(field, order);
        });
    });
    
    // Initial sort and render
    sortData('count', 'desc');
});
</script>
@endif
@endpush
