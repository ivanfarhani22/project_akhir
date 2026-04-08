<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-list-ul"></i>Laporan Detail Nilai
        </h2>
    </div>

    <div class="p-6">
        @forelse($report as $classData)
            <div class="mb-8 pb-8 border-b border-gray-200 last:border-b-0">
                <h3 class="flex items-center gap-2 mb-4">
                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                        {{ $classData['class'] }}
                    </span>
                </h3>
                
                <div class="space-y-2">
                    @foreach($classData['students'] as $idx => $studentData)
                        <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                            <button class="w-full flex items-center justify-between gap-4 px-4 py-3 hover:bg-gray-50 transition cursor-pointer" 
                                onclick="document.getElementById('collapse{{ str_replace(' ', '', $classData['class']) }}{{ $idx }}').classList.toggle('hidden')">
                                <div class="flex items-center gap-3 flex-1">
                                    <i class="fas fa-user-circle text-gray-400"></i>
                                    <span class="font-semibold text-gray-800">{{ $studentData['name'] }}</span>
                                </div>
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                    {{ number_format($studentData['grades']->avg('score'), 2) }}
                                </span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </button>

                            <div id="collapse{{ str_replace(' ', '', $classData['class']) }}{{ $idx }}" class="hidden border-t-2 border-gray-200 {{ $idx === 0 ? '' : 'hidden' }}">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-200">
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tugas</th>
                                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($studentData['grades'] as $grade)
                                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-gray-800">{{ $grade['assignment'] }}</td>
                                                    <td class="px-4 py-3 text-center">
                                                        @php
                                                            $badgeClass = match(true) {
                                                                $grade['score'] >= 80 => 'bg-green-100 text-green-700',
                                                                $grade['score'] >= 70 => 'bg-blue-100 text-blue-700',
                                                                $grade['score'] >= 60 => 'bg-yellow-100 text-yellow-700',
                                                                default => 'bg-red-100 text-red-700'
                                                            };
                                                        @endphp
                                                        <span class="inline-block px-3 py-1 {{ $badgeClass }} rounded-full text-sm font-semibold">
                                                            {{ $grade['score'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                <p class="text-gray-600">Tidak ada data nilai untuk ditampilkan</p>
            </div>
        @endforelse
    </div>
</div>
