<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\LoginBanner;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Batas maksimal banner (untuk tampilan yang profesional)
    const MAX_BANNERS = 5;

    public function edit()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $bannerPath = $settings['login_banner'] ?? null;
        
        // Get multiple banners
        $banners = LoginBanner::orderBy('order')->get();
        $bannerCount = $banners->count();
        $remainingSlots = self::MAX_BANNERS - $bannerCount;
        
        return view('admin.settings.edit', compact('bannerPath', 'banners', 'bannerCount', 'remainingSlots'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'login_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB (old single banner, deprecated)
            'login_banners.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Multiple banners
        ]);

        // Handle new multiple banner uploads
        if ($request->hasFile('login_banners')) {
            $currentCount = LoginBanner::count();
            $newFiles = array_filter($request->file('login_banners')); // Filter out empty files
            
            // Check if total banners exceed max
            if (($currentCount + count($newFiles)) > self::MAX_BANNERS) {
                return redirect()->back()->with('error', 'Jumlah banner tidak boleh melebihi ' . self::MAX_BANNERS . '!');
            }
            
            foreach ($newFiles as $file) {
                $path = $file->store('banners', 'public');
                $order = LoginBanner::max('order') ?? 0;
                
                LoginBanner::create([
                    'image_path' => '/storage/' . $path,
                    'order' => $order + 1,
                    'is_active' => true,
                ]);
            }

            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'update_settings',
                'description' => 'Admin upload banner login (multiple)',
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);
        }

        // Handle old single banner setting (for backward compatibility)
        if ($request->hasFile('login_banner')) {
            $file = $request->file('login_banner');
            $path = $file->store('banners', 'public');
            
            Setting::updateOrCreate(
                ['key' => 'login_banner'],
                ['value' => '/storage/' . $path]
            );
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * Delete banner by ID
     */
    public function deleteBanner($id)
    {
        $banner = LoginBanner::findOrFail($id);
        
        // Delete file
        if (file_exists(public_path($banner->image_path))) {
            unlink(public_path($banner->image_path));
        }
        
        $banner->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_banner',
            'description' => 'Admin delete banner login',
            'ip_address' => request()->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->back()->with('success', 'Banner berhasil dihapus!');
    }

    /**
     * Toggle banner active status
     */
    public function toggleBanner($id)
    {
        $banner = LoginBanner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return redirect()->back()->with('success', 'Status banner berhasil diubah!');
    }

    /**
     * Reorder banners
     */
    public function reorderBanners(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
        ]);

        foreach ($request->input('orders') as $order => $bannerId) {
            LoginBanner::where('id', $bannerId)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan banner berhasil diperbarui!']);
    }

    // ... existing code ...
}
