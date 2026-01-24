<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order', 'asc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'nullable|url',
            'button_text' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('banners', $filename, 'public');
        }
        
        $validated['is_active'] = $request->boolean('is_active');
        $validated['order'] = $validated['order'] ?? Banner::max('order') + 1;

        $banner = Banner::create($validated);
        
        ActivityLog::log('create', "Membuat banner: {$banner->title}", Banner::class, $banner->id);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil dibuat.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'nullable|url',
            'button_text' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $oldValues = $banner->toArray();

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('banners', $filename, 'public');
        }
        
        $validated['is_active'] = $request->boolean('is_active');

        $banner->update($validated);
        
        ActivityLog::log('update', "Mengupdate banner: {$banner->title}", Banner::class, $banner->id, $oldValues, $banner->toArray());

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil diupdate.');
    }

    public function destroy(Banner $banner)
    {
        $title = $banner->title;
        
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        
        $banner->delete();
        
        ActivityLog::log('delete', "Menghapus banner: {$title}", Banner::class);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }
}
