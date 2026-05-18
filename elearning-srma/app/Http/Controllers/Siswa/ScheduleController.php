<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $myClassIds = $request->user()->classes()->pluck('e_classes.id');

        $schedules = Schedule::query()
            ->whereIn('e_class_id', $myClassIds)
            ->with([
                'eClass',
                'classSubject.subject',
                'classSubject.teacher',
            ])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('siswa.schedule.index', compact('schedules'));
    }
}
