<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with(['user', 'category'])->latest();
        
        if ($request->has('category') && $request->category) {
            $query->where('gallery_category_id', $request->category);
        }
        
        $galleries = $query->paginate(12);
        $categories = GalleryCategory::all();
        
        return view('admin.galleries.index', compact('galleries', 'categories'));
    }

    public function create()
    {
        $categories = GalleryCategory::all();
        return view('admin.galleries.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:gallery_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('galleries', $filename, 'public');
        }

        $validated['gallery_category_id'] = $validated['category_id'];
        unset($validated['category_id']);
        $validated['user_id'] = auth()->id();
        $validated['is_featured'] = $request->boolean('is_featured');

        $gallery = Gallery::create($validated);
        
        ActivityLog::log('create', "Menambah galeri: {$gallery->title}", Gallery::class, $gallery->id);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto berhasil ditambahkan ke galeri.');
    }

    public function edit(Gallery $gallery)
    {
        $categories = GalleryCategory::all();
        return view('admin.galleries.edit', compact('gallery', 'categories'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:gallery_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $oldValues = $gallery->toArray();

        if ($request->hasFile('image')) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('galleries', $filename, 'public');
        }
        
        $validated['gallery_category_id'] = $validated['category_id'];
        unset($validated['category_id']);
        $validated['is_featured'] = $request->boolean('is_featured');

        $gallery->update($validated);
        
        ActivityLog::log('update', "Mengupdate galeri: {$gallery->title}", Gallery::class, $gallery->id, $oldValues, $gallery->toArray());

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto galeri berhasil diupdate.');
    }

    public function destroy(Gallery $gallery)
    {
        $title = $gallery->title;
        
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }
        
        $gallery->delete();
        
        ActivityLog::log('delete', "Menghapus galeri: {$title}", Gallery::class);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }

    // Category Management
    public function categories()
    {
        $categories = GalleryCategory::withCount('galleries')->get();
        return view('admin.galleries.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = GalleryCategory::create($validated);
        
        ActivityLog::log('create', "Membuat kategori galeri: {$category->name}", GalleryCategory::class, $category->id);

        return redirect()->route('admin.galleries.categories')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    public function destroyCategory(GalleryCategory $category)
    {
        $name = $category->name;
        $category->delete();
        
        ActivityLog::log('delete', "Menghapus kategori galeri: {$name}", GalleryCategory::class);

        return redirect()->route('admin.galleries.categories')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
