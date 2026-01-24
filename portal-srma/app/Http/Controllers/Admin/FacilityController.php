<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::ordered()->paginate(20);
        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,cukup,kurang',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('facilities', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Facility::create($validated);

        ActivityLog::log('create', "Menambah fasilitas: {$validated['name']}", Facility::class, null);

        return redirect()->route('admin.facilities.index')->with('success', 'Data sarana prasarana berhasil ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,cukup,kurang',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($facility->image) {
                Storage::disk('public')->delete($facility->image);
            }
            $validated['image'] = $request->file('image')->store('facilities', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $facility->update($validated);

        ActivityLog::log('update', "Memperbarui fasilitas: {$facility->name}", Facility::class, $facility->id);

        return redirect()->route('admin.facilities.index')->with('success', 'Data sarana prasarana berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        $facilityName = $facility->name;
        
        if ($facility->image) {
            Storage::disk('public')->delete($facility->image);
        }

        $facility->delete();

        ActivityLog::log('delete', "Menghapus fasilitas: {$facilityName}", Facility::class, null);

        return redirect()->route('admin.facilities.index')->with('success', 'Data sarana prasarana berhasil dihapus.');
    }
}
