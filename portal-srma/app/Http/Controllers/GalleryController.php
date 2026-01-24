<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $categories = GalleryCategory::withCount('galleries')->get();
        
        $query = Gallery::with('category')->latest();
        
        if ($request->has('category') && $request->category) {
            $category = GalleryCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('gallery_category_id', $category->id);
            }
        }
        
        $galleries = $query->paginate(12);
        
        return view('public.gallery.index', compact('galleries', 'categories'));
    }
}
