<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'phone',
        'email',
        'whatsapp',
        'google_maps_embed',
        'facebook',
        'instagram',
        'youtube',
        'twitter',
    ];

    public static function getContact()
    {
        return self::first() ?? new self();
    }
}
