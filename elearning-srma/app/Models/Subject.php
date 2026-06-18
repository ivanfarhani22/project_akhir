<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'description', 'category'];

    public function classes()
    {
        return $this->hasMany(EClass::class);
    }
}
