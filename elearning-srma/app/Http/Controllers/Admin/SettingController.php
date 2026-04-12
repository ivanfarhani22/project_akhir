<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\LoginBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    const MAX_BANNERS = 5;

    public function edit()
    {
        $settings = Setting::all()->pluck('value', 'key');

        $banners = LoginBanner::orderBy('order')->get();
        $bannerCount = $banners->count();
        $remainingSlots = self::MAX_BANNERS - $bannerCount;

        $themeSettings = [
            'primary_color'   => $settings['primary_color']   ?? '#0066cc',
            'secondary_color' => $settings['secondary_color'] ?? '#666666',
            'accent_color'    => $settings['accent_color']    ?? '#ffc107',
            'font_size'       => $settings['font_size']       ?? 'normal',
            'school_name'     => $settings['school_name']     ?? 'E-Learning SRMA',
            'academic_year'   => $settings['academic_year']   ?? date('Y'),
            'semester'        => $settings['semester']        ?? '1',
        ];

        return view('admin.settings.edit', compact('banners', 'bannerCount', 'remainingSlots', 'themeSettings'));
    }

    /**
     * API endpoint untuk mendapatkan semua settings aktif (dipakai layout global)
     */
    public function getActiveSettings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json([
            'font_size'     => $settings['font_size']     ?? 'normal',
            'school_name'   => $settings['school_name']   ?? 'E-Learning SRMA',
            'academic_year' => $settings['academic_year'] ?? date('Y'),
            'semester'      => $settings['semester']      ?? '1',
        ]);
    }

    public function update(Request $request)
    {
        // Jika request berisi file banner, tangani upload saja
        if ($request->hasFile('login_banners')) {
            return $this->uploadBanners($request);
        }

        // Validasi pengaturan akademik
        $validated = $request->validate([
            'school_name'   => 'required|string|max:100',
            'academic_year' => 'required|numeric|digits:4|min:2000|max:2100',
            'semester'      => 'required|in:1,2',
            'font_size'     => 'required|in:small,normal,large',
        ]);

        Setting::updateOrCreate(['key' => 'school_name'],   ['value' => $validated['school_name']]);
        Setting::updateOrCreate(['key' => 'academic_year'], ['value' => $validated['academic_year']]);
        Setting::updateOrCreate(['key' => 'semester'],      ['value' => $validated['semester']]);
        Setting::updateOrCreate(['key' => 'font_size'],     ['value' => $validated['font_size']]);

        \App\Models\ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'update_settings',
            'description' => 'Pembaruan pengaturan sistem',
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }

    /**
     * Upload banner baru
     */
    private function uploadBanners(Request $request)
    {
        $currentCount = LoginBanner::count();
        $remainingSlots = self::MAX_BANNERS - $currentCount;

        if ($remainingSlots <= 0) {
            return redirect()->back()
                ->with('error', 'Batas maksimal banner sudah tercapai (5/5).');
        }

        $request->validate([
            'login_banners'   => 'required|array|max:' . $remainingSlots,
            'login_banners.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        $uploaded = 0;
        foreach ($request->file('login_banners') as $file) {
            if ($currentCount + $uploaded >= self::MAX_BANNERS) {
                break;
            }

            $filename  = 'banner_' . time() . '_' . $uploaded . '.' . $file->getClientOriginalExtension();
            $directory = 'images/banners';
            $file->move(public_path($directory), $filename);

            $lastOrder = LoginBanner::max('order') ?? 0;

            LoginBanner::create([
                'image_path' => $directory . '/' . $filename,
                'is_active'  => true,
                'order'      => $lastOrder + 1,
            ]);

            $uploaded++;
        }

        \App\Models\ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'upload_banner',
            'description' => "Upload {$uploaded} banner login baru",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->back()
            ->with('success', "{$uploaded} banner berhasil diupload!");
    }

    /**
     * Hapus banner by ID
     */
    public function deleteBanner($id)
    {
        $banner = LoginBanner::findOrFail($id);

        $fullPath = public_path($banner->image_path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $banner->delete();

        \App\Models\ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'delete_banner',
            'description' => 'Penghapusan banner login: ' . $banner->image_path,
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Banner berhasil dihapus!');
    }

    /**
     * Toggle status aktif banner
     */
    public function toggleBanner($id)
    {
        $banner = LoginBanner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        $status = $banner->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Banner berhasil {$status}!");
    }

    /**
     * Reorder banners (AJAX)
     */
    public function reorderBanners(Request $request)
    {
        $request->validate(['orders' => 'required|array']);

        foreach ($request->input('orders') as $order => $bannerId) {
            LoginBanner::where('id', $bannerId)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan banner berhasil diperbarui!']);
    }

    /**
     * Reset pengaturan ke default
     */
    public function reset(Request $request)
    {
        $defaultSettings = [
            'primary_color'   => '#0066cc',
            'secondary_color' => '#666666',
            'accent_color'    => '#ffc107',
            'font_size'       => 'normal',
            'school_name'     => 'E-Learning SRMA',
            'academic_year'   => date('Y'),
            'semester'        => '1',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        \App\Models\ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'reset_settings',
            'description' => 'Reset pengaturan sistem ke nilai default',
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Pengaturan direset ke nilai default!');
    }
}