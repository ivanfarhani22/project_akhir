<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::ordered()->paginate(20);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $teacher = Teacher::create($validated);

        ActivityLog::log('create', "Menambah data guru: {$teacher->name}", Teacher::class, $teacher->id);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $validated['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $teacher->update($validated);

        ActivityLog::log('update', "Memperbarui data guru: {$teacher->name}", Teacher::class, $teacher->id);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacherName = $teacher->name;
        
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        ActivityLog::log('delete', "Menghapus data guru: {$teacherName}", Teacher::class, null);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
