<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Banner;
use App\Models\Agenda;
use App\Models\Gallery;
use App\Models\Profile;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Announcement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->take(5)->get();
        $latestNews = News::published()->latest()->take(3)->get();
        $announcements = Announcement::active()->latest()->take(5)->get();
        $upcomingAgendas = Agenda::upcoming()->take(5)->get();
        $featuredGalleries = Gallery::featured()->latest()->take(6)->get();
        $contact = Contact::getContact();
        
        // Data sekolah
        $schoolData = [
            'total_siswa_laki' => Setting::getValue('total_siswa_laki', 40),
            'total_siswa_perempuan' => Setting::getValue('total_siswa_perempuan', 35),
            'total_guru' => Setting::getValue('total_guru', 16),
            'total_staff' => Setting::getValue('total_staff', 27),
        ];

        return view('public.home', compact(
            'banners',
            'latestNews',
            'announcements',
            'upcomingAgendas',
            'featuredGalleries',
            'contact',
            'schoolData'
        ));
    }
}
