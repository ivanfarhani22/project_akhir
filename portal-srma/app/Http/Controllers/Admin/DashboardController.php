<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Agenda;
use App\Models\Gallery;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_news' => News::count(),
            'published_news' => News::published()->count(),
            'total_announcements' => Announcement::count(),
            'active_announcements' => Announcement::active()->count(),
            'total_agendas' => Agenda::count(),
            'upcoming_agendas' => Agenda::upcoming()->count(),
            'total_galleries' => Gallery::count(),
        ];
        
        $recentNews = News::latest()->take(5)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(5)->get();
        $upcomingAgendas = Agenda::upcoming()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recentNews', 'recentActivities', 'upcomingAgendas'));
    }
}
