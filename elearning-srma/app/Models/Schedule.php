<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'e_class_id',
        'class_subject_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'notes'
    ];

    public function eClass()
    {
        return $this->belongsTo(EClass::class);
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }

    public function subject()
    {
        return $this->classSubject->subject();
    }

    public function teacher()
    {
        return $this->classSubject->teacher();
    }
}
