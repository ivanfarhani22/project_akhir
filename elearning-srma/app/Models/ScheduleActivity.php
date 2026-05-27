<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleActivity extends Model
{
    protected $fillable = [
        'schedule_id',
        'student_id',
        'activity_date',
        'score',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
