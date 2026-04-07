<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginBanner extends Model
{
    protected $table = 'login_banners';
    
    protected $fillable = ['image_path', 'order', 'is_active'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
    
    /**
     * Scope untuk get active banners saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
    
    /**
     * Get semua active banners
     */
    public static function getActiveBanners()
    {
        return self::active()->get();
    }
    
    /**
     * Get count active banners
     */
    public static function getActiveBannersCount()
    {
        return self::where('is_active', true)->count();
    }
}
