<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'student_id',
        'report_date',
        'created_by',
        'created_by_role',
        'notes',
        'average_grade',
        'attendance_present',
        'attendance_total',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
