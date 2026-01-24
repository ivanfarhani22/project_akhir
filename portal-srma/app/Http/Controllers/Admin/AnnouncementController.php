<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('user')->latest();
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }
        
        $announcements = $query->paginate(10);
        
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
            'is_active' => 'boolean',
            'expired_at' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_important'] = $request->boolean('is_important');
        
        // Convert is_active to status
        $validated['status'] = $request->boolean('is_active') ? 'published' : 'draft';
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('announcements', $filename, 'public');
            $validated['attachment'] = $path;
            $validated['attachment_name'] = $file->getClientOriginalName();
        }

        // Remove is_active from validated data as it's not in the model
        unset($validated['is_active']);

        $announcement = Announcement::create($validated);
        
        ActivityLog::log('create', "Membuat pengumuman: {$announcement->title}", Announcement::class, $announcement->id);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
            'is_active' => 'boolean',
            'expired_at' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        $oldValues = $announcement->toArray();
        
        $validated['is_important'] = $request->boolean('is_important');
        
        // Convert is_active to status
        $validated['status'] = $request->boolean('is_active') ? 'published' : 'draft';
        
        if ($validated['status'] === 'published' && !$announcement->published_at) {
            $validated['published_at'] = now();
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('announcements', $filename, 'public');
            $validated['attachment'] = $path;
            $validated['attachment_name'] = $file->getClientOriginalName();
        }
        
        // Handle remove attachment
        if ($request->boolean('remove_attachment') && $announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
            $validated['attachment'] = null;
            $validated['attachment_name'] = null;
        }

        // Remove is_active from validated data as it's not in the model
        unset($validated['is_active']);

        $announcement->update($validated);
        
        ActivityLog::log('update', "Mengupdate pengumuman: {$announcement->title}", Announcement::class, $announcement->id, $oldValues, $announcement->toArray());

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diupdate.');
    }

    public function destroy(Announcement $announcement)
    {
        $title = $announcement->title;
        
        // Delete attachment if exists
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }
        
        $announcement->delete();
        
        ActivityLog::log('delete', "Menghapus pengumuman: {$title}", Announcement::class);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
