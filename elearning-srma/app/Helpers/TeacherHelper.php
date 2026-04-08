<?php

namespace App\Helpers;

use App\Models\ClassSubject;

class TeacherHelper
{
    /**
     * Get all class subjects taught by a teacher
     * @param int|string $teacherId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTeachingClassSubjects($teacherId)
    {
        return ClassSubject::where('teacher_id', $teacherId)
            ->with('eClass', 'subject')
            ->get();
    }

    /**
     * Get count of subjects taught by teacher
     * @param int|string $teacherId
     * @return int
     */
    public static function getTeachingClassSubjectCount($teacherId)
    {
        return ClassSubject::where('teacher_id', $teacherId)->count();
    }

    /**
     * Get all unique classes where teacher teaches
     * @param int|string $teacherId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTeachingClasses($teacherId)
    {
        return \App\Models\EClass::whereHas('classSubjects', fn($q) => $q->where('teacher_id', $teacherId))
            ->with('classSubjects')
            ->distinct()
            ->get();
    }
}
