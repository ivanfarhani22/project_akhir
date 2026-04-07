<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EClass extends Model
{
    protected $table = 'e_classes';
    protected $fillable = ['name', 'description', 'day_of_week', 'start_time', 'end_time', 'room'];

    // Relasi dengan ClassSubject (subjects dalam kelas dengan teachers)
    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

    // Relasi dengan Schedules (timetable)
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Helper untuk mendapatkan semua subjects dalam kelas
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects');
    }

    // Relasi dengan Students
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'e_class_id', 'student_id');
    }

    // Relasi dengan Materials
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    // Relasi dengan Assignments
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // Helpers untuk akses subject dan teacher dari classSubjects
    public function getSubjectAttribute()
    {
        return $this->classSubjects?->first()?->subject;
    }

    public function getTeacherAttribute()
    {
        return $this->classSubjects?->first()?->teacher;
    }

    // Helper untuk check apakah user adalah teacher di kelas ini
    public function isTeachedBy($userId)
    {
        return $this->classSubjects()->where('teacher_id', $userId)->exists();
    }
}
