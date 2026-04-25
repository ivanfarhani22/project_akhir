<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['e_class_id', 'title', 'display_name', 'description', 'file_path', 'file_type', 'version', 'uploaded_by'];

    public function eClass()
    {
        return $this->belongsTo(EClass::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
