@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')
<div class="grid lg:grid-cols-2 gap-6">
    <!-- Contact Settings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Kontak</h2>
        
        <form action="{{ route('admin.settings.contact') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="address" id="address" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ $contact->address ?? '' }}</textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ $contact->phone ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ $contact->whatsapp ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ $contact->email ?? '' }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <div class="border-t pt-4 mt-4">
                <h3 class="font-medium text-gray-800 mb-4">Media Sosial</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                        <input type="url" name="facebook" id="facebook" value="{{ $contact->facebook ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="https://facebook.com/...">
                    </div>
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                        <input type="url" name="instagram" id="instagram" value="{{ $contact->instagram ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="https://instagram.com/...">
                    </div>
                    <div>
                        <label for="youtube" class="block text-sm font-medium text-gray-700 mb-2">YouTube</label>
                        <input type="url" name="youtube" id="youtube" value="{{ $contact->youtube ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>
            
            <div class="border-t pt-4 mt-4">
                <label for="google_maps_embed" class="block text-sm font-medium text-gray-700 mb-2">Google Maps Embed</label>
                <textarea name="google_maps_embed" id="google_maps_embed" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-mono text-xs"
                          placeholder="<iframe src='...'></iframe>">{{ $contact->google_maps_embed ?? '' }}</textarea>
            </div>
            
            <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors mt-6">
                Simpan Kontak
            </button>
        </form>
    </div>
    
    <!-- Site Settings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Pengaturan Situs</h2>
        
        <form action="{{ route('admin.settings.site') }}" method="POST">
            @csrf
            @method('PUT')
            
            @foreach($settings as $setting)
            <div class="mb-4">
                <label for="setting_{{ $setting->id }}" class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->key }}</label>
                <input type="text" name="settings[{{ $setting->id }}]" id="setting_{{ $setting->id }}" value="{{ $setting->value }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            @endforeach
            
            <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                Simpan Pengaturan
            </button>
        </form>
    </div>
    
    <!-- Change Password -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Ganti Password</h2>
        
        <form action="{{ route('admin.settings.password') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('current_password') border-red-500 @enderror">
                @error('current_password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('password') border-red-500 @enderror">
                @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                Ganti Password
            </button>
        </form>
    </div>
</div>
@endsection
