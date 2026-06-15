<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'e_class_id',
        'class_subject_id',
        'custom_title',
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
        return $this->hasOneThrough(
            Subject::class,
            ClassSubject::class,
            'id',
            'id',
            'class_subject_id',
            'subject_id'
        );
    }

    public function teacher()
    {
        return $this->hasOneThrough(
            User::class,
            ClassSubject::class,
            'id',
            'id',
            'class_subject_id',
            'teacher_id'
        );
    }

    /**
     * Judul yang ditampilkan di jadwal.
     * - Jika schedule terhubung mapel: pakai nama Subject
     * - Jika custom (non mapel): pakai custom_title
     */
    public function getDisplayTitleAttribute(): string
    {
        return (string) (
            $this->classSubject?->subject?->name
            ?? $this->custom_title
            ?? '-'
        );
    }

    public function activities()
    {
        return $this->hasMany(ScheduleActivity::class);
    }
}
