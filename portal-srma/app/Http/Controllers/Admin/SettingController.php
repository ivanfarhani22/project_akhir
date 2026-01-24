<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\Contact;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        $contact = Contact::first() ?? new Contact();
        
        return view('admin.settings.index', compact('settings', 'contact'));
    }
    
    public function updateProfiles(Request $request)
    {
        foreach ($request->profiles as $id => $value) {
            Profile::where('id', $id)->update(['value' => $value]);
        }
        
        ActivityLog::log('update', 'Mengupdate profil sekolah', Profile::class);
        
        return redirect()->route('admin.settings')
            ->with('success', 'Profil sekolah berhasil diupdate.');
    }
    
    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'whatsapp' => 'nullable|string|max:20',
            'google_maps_embed' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'twitter' => 'nullable|url',
        ]);

        Contact::updateOrCreate(['id' => 1], $validated);
        
        ActivityLog::log('update', 'Mengupdate kontak sekolah', Contact::class);

        return redirect()->route('admin.settings')
            ->with('success', 'Kontak sekolah berhasil diupdate.');
    }
    
    public function updateSite(Request $request)
    {
        foreach ($request->settings as $id => $value) {
            Setting::where('id', $id)->update(['value' => $value]);
        }
        
        ActivityLog::log('update', 'Mengupdate pengaturan situs', Setting::class);
        
        return redirect()->route('admin.settings')
            ->with('success', 'Pengaturan situs berhasil diupdate.');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        auth()->user()->update([
            'password' => bcrypt($request->password)
        ]);
        
        ActivityLog::log('update', 'Mengubah password admin', User::class);
        
        return redirect()->route('admin.settings')
            ->with('success', 'Password berhasil diubah.');
    }

    public function profile()
    {
        $profiles = [
            'dasar_hukum' => Profile::getValue('dasar_hukum', ''),
            'visi' => Profile::getValue('visi', ''),
            'misi' => Profile::getValue('misi', ''),
            'struktur_organisasi_image' => Profile::getValue('struktur_organisasi_image', ''),
        ];
        
        return view('admin.settings.profile', compact('profiles'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'dasar_hukum' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'struktur_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Profile::setValue('dasar_hukum', 'Dasar Hukum dan Legalitas', $validated['dasar_hukum'] ?? '', 'html');
        Profile::setValue('visi', 'Visi', $validated['visi'] ?? '', 'html');
        Profile::setValue('misi', 'Misi', $validated['misi'] ?? '', 'html');

        if ($request->hasFile('struktur_image')) {
            $oldImage = Profile::getValue('struktur_organisasi_image', '');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            
            $file = $request->file('struktur_image');
            $filename = time() . '_struktur.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profiles', $filename, 'public');
            Profile::setValue('struktur_organisasi_image', 'Gambar Struktur Organisasi', $path, 'image');
        }
        
        ActivityLog::log('update', 'Mengupdate profil sekolah', Profile::class);

        return redirect()->route('admin.settings.profile')
            ->with('success', 'Profil sekolah berhasil diupdate.');
    }

    public function deleteStrukturImage()
    {
        $oldImage = Profile::getValue('struktur_organisasi_image', '');
        if ($oldImage) {
            Storage::disk('public')->delete($oldImage);
            Profile::where('key', 'struktur_organisasi_image')->delete();
            ActivityLog::log('delete', 'Menghapus gambar struktur organisasi', Profile::class);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    public function dataSekolah()
    {
        $data = [
            'total_siswa_laki' => Setting::getValue('total_siswa_laki', 40),
            'total_siswa_perempuan' => Setting::getValue('total_siswa_perempuan', 35),
            'total_guru' => Setting::getValue('total_guru', 16),
            'total_kepala_sekolah' => Setting::getValue('total_kepala_sekolah', 1),
            'total_wali_asrama' => Setting::getValue('total_wali_asrama', 3),
            'total_wali_asuh' => Setting::getValue('total_wali_asuh', 8),
            'total_keamanan' => Setting::getValue('total_keamanan', 3),
            'total_kebersihan' => Setting::getValue('total_kebersihan', 4),
            'total_juru_masak' => Setting::getValue('total_juru_masak', 5),
            'total_operator' => Setting::getValue('total_operator', 1),
            'total_bendahara' => Setting::getValue('total_bendahara', 1),
            'total_tu' => Setting::getValue('total_tu', 1),
        ];
        
        return view('admin.settings.data-sekolah', compact('data'));
    }

    public function updateDataSekolah(Request $request)
    {
        $validated = $request->validate([
            'total_siswa_laki' => 'required|integer|min:0',
            'total_siswa_perempuan' => 'required|integer|min:0',
            'total_guru' => 'required|integer|min:0',
            'total_kepala_sekolah' => 'required|integer|min:0',
            'total_wali_asrama' => 'required|integer|min:0',
            'total_wali_asuh' => 'required|integer|min:0',
            'total_keamanan' => 'required|integer|min:0',
            'total_kebersihan' => 'required|integer|min:0',
            'total_juru_masak' => 'required|integer|min:0',
            'total_operator' => 'required|integer|min:0',
            'total_bendahara' => 'required|integer|min:0',
            'total_tu' => 'required|integer|min:0',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }
        
        ActivityLog::log('update', 'Mengupdate data sekolah', Setting::class);

        return redirect()->route('admin.settings.data-sekolah')
            ->with('success', 'Data sekolah berhasil diupdate.');
    }

    public function elearning()
    {
        $elearningUrl = Setting::getValue('elearning_url', '');
        return view('admin.settings.elearning', compact('elearningUrl'));
    }

    public function updateElearning(Request $request)
    {
        $validated = $request->validate([
            'elearning_url' => 'nullable|url',
        ]);

        Setting::setValue('elearning_url', $validated['elearning_url'] ?? '', 'url');
        
        ActivityLog::log('update', 'Mengupdate URL E-Learning', Setting::class);

        return redirect()->route('admin.settings.elearning')
            ->with('success', 'URL E-Learning berhasil diupdate.');
    }
}
