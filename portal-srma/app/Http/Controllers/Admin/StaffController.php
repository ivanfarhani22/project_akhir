<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::ordered()->paginate(20);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('staff', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Staff::create($validated);

        ActivityLog::log('create', "Menambah data tenaga kependidikan: {$validated['name']}", Staff::class, null);

        return redirect()->route('admin.staff.index')->with('success', 'Data tenaga kependidikan berhasil ditambahkan.');
    }

    public function edit(Staff $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($staff->photo) {
                Storage::disk('public')->delete($staff->photo);
            }
            $validated['photo'] = $request->file('photo')->store('staff', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $staff->update($validated);

        ActivityLog::log('update', "Memperbarui data tenaga kependidikan: {$staff->name}", Staff::class, $staff->id);

        return redirect()->route('admin.staff.index')->with('success', 'Data tenaga kependidikan berhasil diperbarui.');
    }

    public function destroy(Staff $staff)
    {
        $staffName = $staff->name;
        
        if ($staff->photo) {
            Storage::disk('public')->delete($staff->photo);
        }

        $staff->delete();

        ActivityLog::log('delete', "Menghapus data tenaga kependidikan: {$staffName}", Staff::class, null);

        return redirect()->route('admin.staff.index')->with('success', 'Data tenaga kependidikan berhasil dihapus.');
    }
}
