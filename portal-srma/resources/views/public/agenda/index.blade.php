@extends('layouts.public')

@section('title', 'Agenda Kegiatan - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex flex-wrap items-center gap-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Agenda Kegiatan</span></li>
            </ol>
        </nav>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white">Agenda Kegiatan</h1>
        <p class="text-gray-400 mt-2 text-sm sm:text-base">Kalender kegiatan SRMA 25 Lamongan</p>
    </div>
</section>

<!-- Calendar Section -->
<section class="py-8 sm:py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Calendar -->
            <div class="lg:col-span-2 order-1">
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                    <!-- Calendar Header -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-4 sm:mb-6">
                        @php
                            $prevMonth = $month - 1;
                            $prevYear = $year;
                            if ($prevMonth < 1) {
                                $prevMonth = 12;
                                $prevYear--;
                            }
                            
                            $nextMonth = $month + 1;
                            $nextYear = $year;
                            if ($nextMonth > 12) {
                                $nextMonth = 1;
                                $nextYear++;
                            }
                            
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        
                        <!-- Navigation Arrows & Month/Year Selectors -->
                        <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-center">
                            <a href="{{ route('agenda.index', ['month' => $prevMonth, 'year' => $prevYear]) }}" 
                               class="p-2 hover:bg-gray-100 rounded-lg transition-colors" title="Bulan Sebelumnya">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                            
                            <!-- Month & Year Selector Form -->
                            <form action="{{ route('agenda.index') }}" method="GET" class="flex items-center gap-2" id="calendarNavForm">
                                <select name="month" 
                                        class="text-sm sm:text-base font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer hover:bg-gray-50 transition-colors"
                                        onchange="document.getElementById('calendarNavForm').submit()">
                                    @foreach($months as $m => $monthName)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $monthName }}</option>
                                    @endforeach
                                </select>
                                
                                <input type="number" 
                                       name="year" 
                                       value="{{ $year }}"
                                       min="1900" 
                                       max="2100"
                                       class="w-20 sm:w-24 text-sm sm:text-base font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 hover:bg-gray-50 transition-colors text-center"
                                       onchange="document.getElementById('calendarNavForm').submit()"
                                       onkeydown="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('calendarNavForm').submit(); }">
                            </form>
                            
                            <a href="{{ route('agenda.index', ['month' => $nextMonth, 'year' => $nextYear]) }}" 
                               class="p-2 hover:bg-gray-100 rounded-lg transition-colors" title="Bulan Selanjutnya">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        
                        <!-- Today Button -->
                        <a href="{{ route('agenda.index', ['month' => now()->month, 'year' => now()->year]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-primary-600 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Hari Ini
                        </a>
                    </div>
                    
                    <!-- Legend -->
                    <div class="flex flex-wrap gap-3 sm:gap-4 mb-4 text-xs sm:text-sm">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-gray-600">Hari Libur/Minggu</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-primary-500"></span>
                            <span class="text-gray-600">Hari Ini</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-primary-200"></span>
                            <span class="text-gray-600">Ada Agenda</span>
                        </div>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 gap-0.5 sm:gap-1">
                        <!-- Day Headers -->
                        @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $index => $day)
                        <div class="text-center text-xs sm:text-sm font-medium py-2 {{ $index === 0 ? 'text-red-500' : 'text-gray-500' }}">
                            {{ $day }}
                        </div>
                        @endforeach
                        
                        <!-- Calendar Days -->
                        @foreach($calendarData as $week)
                            @foreach($week as $dayData)
                                @if($dayData)
                                @php
                                    $isRedDay = $dayData['isSunday'] || $dayData['isHoliday'];
                                    $bgClass = '';
                                    $borderClass = 'border-gray-100';
                                    
                                    if ($dayData['isToday']) {
                                        $bgClass = 'bg-primary-50';
                                        $borderClass = 'border-primary-300';
                                    } elseif ($isRedDay) {
                                        $bgClass = 'bg-red-50';
                                        $borderClass = 'border-red-100';
                                    }
                                @endphp
                                <div class="min-h-[60px] sm:min-h-[80px] border {{ $borderClass }} rounded-lg p-1 {{ $bgClass }} relative group"
                                     @if($dayData['holidayName']) 
                                     title="{{ $dayData['holidayName'] }}"
                                     @endif>
                                    <div class="text-xs sm:text-sm font-medium {{ $dayData['isToday'] ? 'text-primary-600 font-bold' : ($isRedDay ? 'text-red-500 font-semibold' : 'text-gray-700') }}">
                                        {{ $dayData['day'] }}
                                    </div>
                                    
                                    {{-- Holiday name tooltip on hover --}}
                                    @if($dayData['holidayName'])
                                    <div class="hidden sm:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                        {{ $dayData['holidayName'] }}
                                    </div>
                                    @endif
                                    
                                    {{-- Holiday indicator for mobile --}}
                                    @if($dayData['isHoliday'])
                                    <div class="sm:hidden text-[8px] text-red-500 truncate leading-tight mt-0.5">
                                        {{ Str::limit($dayData['holidayName'], 10) }}
                                    </div>
                                    @endif
                                    
                                    @if($dayData['agendas']->count() > 0)
                                        @foreach($dayData['agendas']->take(2) as $agenda)
                                        <a href="{{ route('agenda.show', $agenda->slug) }}" 
                                           class="hidden sm:block text-xs p-1 mt-1 bg-primary-100 text-primary-700 rounded truncate hover:bg-primary-200">
                                            {{ $agenda->title }}
                                        </a>
                                        @endforeach
                                        @if($dayData['agendas']->count() > 2)
                                        <span class="hidden sm:block text-xs text-gray-500">+{{ $dayData['agendas']->count() - 2 }} lainnya</span>
                                        @endif
                                        
                                        {{-- Mobile: show dot indicator --}}
                                        <div class="sm:hidden flex justify-center mt-1">
                                            <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                                            @if($dayData['agendas']->count() > 1)
                                            <span class="w-1.5 h-1.5 bg-primary-300 rounded-full ml-0.5"></span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @else
                                <div class="min-h-[60px] sm:min-h-[80px] bg-gray-50 rounded-lg"></div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                    
                    {{-- Holiday List for this month --}}
                    @php
                        $monthHolidayList = collect($holidays)->filter(fn($h) => $h['is_holiday'] ?? false);
                    @endphp
                    @if($monthHolidayList->count() > 0)
                    <div class="mt-4 sm:mt-6 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Hari Libur Nasional Bulan Ini:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($monthHolidayList as $day => $holidayInfo)
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 text-red-700 rounded-full text-xs">
                                <span class="font-semibold">{{ $day }}</span>
                                <span>{{ $holidayInfo['holiday_name'] }}</span>
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Agenda List -->
                @if($agendas->count() > 0)
                <div class="mt-6 sm:mt-8">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Agenda Bulan Ini</h3>
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($agendas as $agenda)
                        <a href="{{ route('agenda.show', $agenda->slug) }}" class="flex items-start space-x-3 sm:space-x-4 bg-white rounded-xl shadow-sm p-3 sm:p-4 hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0 w-12 h-12 sm:w-16 sm:h-16 bg-primary-600 text-white rounded-lg flex flex-col items-center justify-center">
                                <span class="text-lg sm:text-xl font-bold leading-none">{{ $agenda->start_date->format('d') }}</span>
                                <span class="text-[10px] sm:text-xs uppercase">{{ $agenda->start_date->translatedFormat('M') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] sm:text-xs font-medium 
                                        {{ $agenda->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $agenda->status === 'ongoing' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $agenda->status === 'completed' ? 'bg-gray-100 text-gray-700' : '' }}
                                        {{ $agenda->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($agenda->status) }}
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-semibold text-gray-800 hover:text-primary-600 line-clamp-2">{{ $agenda->title }}</h4>
                                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500">
                                    @if($agenda->formatted_time)
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $agenda->formatted_time }}
                                    </span>
                                    @endif
                                    @if($agenda->location)
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span class="truncate max-w-[150px] sm:max-w-none">{{ $agenda->location }}</span>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar - Upcoming -->
            <div class="lg:col-span-1 order-2">
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 lg:sticky lg:top-24">
                    <h3 class="font-semibold text-gray-800 mb-4">Agenda Mendatang</h3>
                    
                    @if($upcomingAgendas->count() > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($upcomingAgendas as $upcoming)
                        <a href="{{ route('agenda.show', $upcoming->slug) }}" class="flex items-start space-x-3 group">
                            <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-lg flex flex-col items-center justify-center group-hover:bg-primary-100">
                                <span class="text-xs sm:text-sm font-bold text-gray-800 group-hover:text-primary-600">{{ $upcoming->start_date->format('d') }}</span>
                                <span class="text-[10px] sm:text-xs text-gray-500 uppercase">{{ $upcoming->start_date->translatedFormat('M') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs sm:text-sm font-medium text-gray-800 line-clamp-2 group-hover:text-primary-600">{{ $upcoming->title }}</h4>
                                @if($upcoming->location)
                                <p class="text-[10px] sm:text-xs text-gray-500 mt-1 truncate">{{ $upcoming->location }}</p>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Tidak ada agenda mendatang.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
