<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'content',
        'type',
    ];

    public static function getValue(string $key, $default = null)
    {
        $profile = self::where('key', $key)->first();
        return $profile ? $profile->content : $default;
    }

    public static function setValue(string $key, string $title, $content, string $type = 'text')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['title' => $title, 'content' => $content, 'type' => $type]
        );
    }
}
