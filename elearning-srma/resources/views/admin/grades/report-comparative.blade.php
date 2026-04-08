<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-chart-column"></i>Laporan Perbandingan Nilai per Tugas
        </h2>
    </div>

    <div class="p-6">
        <!-- Main Table -->
        <div class="overflow-x-auto mb-6">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tugas</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Jumlah</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Rata-rata</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Tertinggi</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Terendah</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Progres</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $assignmentData)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $assignmentData['assignment'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                    {{ $assignmentData['count'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                    {{ number_format($assignmentData['average'], 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                    {{ number_format($assignmentData['highest'], 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                                    {{ number_format($assignmentData['lowest'], 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-8 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center" 
                                        style="width: {{ ($assignmentData['average'] / 100) * 100 }}%">
                                        <span class="text-xs font-bold text-white">{{ number_format(($assignmentData['average'] / 100) * 100, 1) }}%</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                                <p>Tidak ada data nilai untuk ditampilkan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Statistics Section -->
        @if($report->count() > 0)
            <div class="border-t-2 border-gray-200 pt-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Overall Stats -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-chart-pie text-red-600 mr-2"></i>Statistik Keseluruhan
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="font-semibold text-gray-700">Rata-rata Seluruh Tugas:</span>
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                    {{ number_format($report->avg('average'), 2) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="font-semibold text-gray-700">Nilai Tertinggi:</span>
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                    {{ number_format($report->max('highest'), 2) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="font-semibold text-gray-700">Nilai Terendah:</span>
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                                    {{ number_format($report->min('lowest'), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Distribution -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-chart-bar text-red-600 mr-2"></i>Distribusi Nilai
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="text-center p-4 bg-green-50 rounded-lg border-2 border-green-200">
                                <small class="block text-sm font-semibold text-gray-600 mb-2">Excellent (≥90)</small>
                                <span class="block text-3xl font-bold text-green-700">
                                    {{ $report->filter(fn($r) => $r['average'] >= 90)->count() }}
                                </span>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                                <small class="block text-sm font-semibold text-gray-600 mb-2">Good (80-89)</small>
                                <span class="block text-3xl font-bold text-blue-700">
                                    {{ $report->filter(fn($r) => $r['average'] >= 80 && $r['average'] < 90)->count() }}
                                </span>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-lg border-2 border-yellow-200">
                                <small class="block text-sm font-semibold text-gray-600 mb-2">Fair (70-79)</small>
                                <span class="block text-3xl font-bold text-yellow-700">
                                    {{ $report->filter(fn($r) => $r['average'] >= 70 && $r['average'] < 80)->count() }}
                                </span>
                            </div>
                            <div class="text-center p-4 bg-red-50 rounded-lg border-2 border-red-200">
                                <small class="block text-sm font-semibold text-gray-600 mb-2">Poor (<70)</small>
                                <span class="block text-3xl font-bold text-red-700">
                                    {{ $report->filter(fn($r) => $r['average'] < 70)->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
