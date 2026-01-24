<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::published()->latest();
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate(9);
        
        return view('public.news.index', compact('news'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)->published()->firstOrFail();
        $news->incrementViews();
        
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(3)
            ->get();
        
        return view('public.news.show', compact('news', 'relatedNews'));
    }
}
