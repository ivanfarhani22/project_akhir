<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::active()->latest()->paginate(10);
        
        return view('public.announcements.index', compact('announcements'));
    }

    public function show($slug)
    {
        $announcement = Announcement::where('slug', $slug)->active()->firstOrFail();
        
        return view('public.announcements.show', compact('announcement'));
    }
}
