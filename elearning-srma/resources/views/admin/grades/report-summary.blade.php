<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-chart-pie"></i>Ringkasan Nilai
        </h2>
    </div>

    <div class="p-6">
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg p-6">
                <p class="text-red-100 text-sm font-semibold mb-1">Total Nilai</p>
                <p class="text-4xl font-bold">{{ $report['total_grades'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-6">
                <p class="text-green-100 text-sm font-semibold mb-1">Rata-rata</p>
                <p class="text-4xl font-bold">{{ number_format($report['average_score'], 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-6">
                <p class="text-blue-100 text-sm font-semibold mb-1">Tertinggi</p>
                <p class="text-4xl font-bold">{{ number_format($report['highest_score'], 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg p-6">
                <p class="text-yellow-100 text-sm font-semibold mb-1">Terendah</p>
                <p class="text-4xl font-bold">{{ number_format($report['lowest_score'], 2) }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-list mr-2 text-red-600"></i>Nilai per Kelas
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kelas</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Jumlah Nilai</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report['by_class'] as $className => $classReport)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $className }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                        {{ $classReport['count'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 max-w-xs">
                                            <div class="h-6 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center" 
                                                    style="width: {{ ($classReport['average'] / 100) * 100 }}%">
                                                    <span class="text-xs font-bold text-white">{{ number_format($classReport['average'], 1) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                                    <p>Tidak ada data nilai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
