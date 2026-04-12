<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['e_class_id', 'title', 'description', 'file_path', 'deadline'];
    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function eClass()
    {
        return $this->belongsTo(EClass::class);
    }

    // Optional relation (some controllers reference assignment->classSubject)
    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class, 'class_subject_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
