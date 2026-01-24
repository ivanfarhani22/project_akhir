<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Services\IndonesianHolidayService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgendaController extends Controller
{
    protected $holidayService;

    public function __construct(IndonesianHolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $agendas = Agenda::whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->orderBy('start_date', 'asc')
            ->get();
        
        $upcomingAgendas = Agenda::upcoming()->take(5)->get();
        
        // Get Indonesian holidays for this month
        $holidays = $this->holidayService->getMonthHolidays($month, $year);
        
        // Generate calendar data with holidays
        $calendarData = $this->generateCalendarData($month, $year, $agendas, $holidays);
        
        return view('public.agenda.index', compact('agendas', 'upcomingAgendas', 'calendarData', 'month', 'year', 'holidays'));
    }

    public function show($slug)
    {
        $agenda = Agenda::where('slug', $slug)->firstOrFail();
        
        $relatedAgendas = Agenda::where('id', '!=', $agenda->id)
            ->upcoming()
            ->take(3)
            ->get();
        
        return view('public.agenda.show', compact('agenda', 'relatedAgendas'));
    }

    private function generateCalendarData($month, $year, $agendas, $holidays = [])
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $calendar = [];
        $currentWeek = [];
        
        // Fill empty days at start
        for ($i = 0; $i < $startOfMonth->dayOfWeek; $i++) {
            $currentWeek[] = null;
        }
        
        // Fill days
        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dayAgendas = $agendas->filter(function ($agenda) use ($date) {
                return $date->between($agenda->start_date, $agenda->end_date ?? $agenda->start_date);
            });
            
            // Get holiday info for this day
            $holidayInfo = $holidays[$day] ?? null;
            
            $currentWeek[] = [
                'day' => $day,
                'date' => $date,
                'agendas' => $dayAgendas,
                'isToday' => $date->isToday(),
                'isSunday' => $date->dayOfWeek === Carbon::SUNDAY,
                'isHoliday' => $holidayInfo['is_holiday'] ?? false,
                'holidayName' => $holidayInfo['holiday_name'] ?? null,
            ];
            
            if (count($currentWeek) == 7) {
                $calendar[] = $currentWeek;
                $currentWeek = [];
            }
        }
        
        // Fill empty days at end
        if (count($currentWeek) > 0) {
            while (count($currentWeek) < 7) {
                $currentWeek[] = null;
            }
            $calendar[] = $currentWeek;
        }
        
        return $calendar;
    }
}
