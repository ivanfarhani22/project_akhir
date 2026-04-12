<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $fillable = [
        'class_subject_id',
        'opened_by',
        'attendance_date',
        'opened_at',
        'closed_at',
        'status',
        'notes'
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    // Relationships
    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }

    public function eClass()
    {
        return $this->classSubject->eClass();
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    // Helper methods
    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function getAttendancePercentage()
    {
        $total = $this->records->count();
        if ($total === 0) return 0;
        
        $present = $this->records->whereIn('status', ['present', 'late'])->count();
        return round(($present / $total) * 100, 2);
    }

    public function getClassAttribute()
    {
        return $this->classSubject?->eClass;
    }
}
