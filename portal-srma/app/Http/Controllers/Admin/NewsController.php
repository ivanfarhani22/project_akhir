<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('user')->latest();
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }
        
        $news = $query->paginate(10);
        
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $validated['thumbnail'] = $file->storeAs('news', $filename, 'public');
        }

        $validated['user_id'] = auth()->id();
        
        // Convert is_published to status
        $validated['status'] = $request->boolean('is_published') ? 'published' : 'draft';
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Remove is_published and image from validated data
        unset($validated['is_published'], $validated['image']);

        // Observer akan otomatis mencatat log
        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old thumbnail
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $validated['thumbnail'] = $file->storeAs('news', $filename, 'public');
        }
        
        // Handle remove image
        if ($request->boolean('remove_image') && $news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
            $validated['thumbnail'] = null;
        }
        
        // Convert is_published to status
        $validated['status'] = $request->boolean('is_published') ? 'published' : 'draft';
        
        if ($validated['status'] === 'published' && !$news->published_at) {
            $validated['published_at'] = now();
        }

        // Remove is_published and image from validated data
        unset($validated['is_published'], $validated['image']);

        // Observer akan otomatis mencatat log dengan old_values dan new_values
        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diupdate.');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        
        // Observer akan otomatis mencatat log
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
