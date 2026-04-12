<?php

namespace App\Services;

use App\Models\EClass;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Notify all students in a class.
     */
    public static function notifyClassStudents(EClass $class, array $payload): void
    {
        $students = $class->students()->where('role', 'siswa')->get();

        foreach ($students as $student) {
            static::notifyUser($student, $payload);
        }
    }

    /**
     * Notify the teacher(s) of a class.
     *
     * Current model uses classSubjects with teacher_id.
     */
    public static function notifyClassTeachers(EClass $class, array $payload): void
    {
        $teacherIds = $class->classSubjects()->pluck('teacher_id')->filter()->unique();
        if ($teacherIds->isEmpty()) {
            return;
        }

        $teachers = User::whereIn('id', $teacherIds)->where('role', 'guru')->get();

        foreach ($teachers as $teacher) {
            static::notifyUser($teacher, $payload);
        }
    }

    /**
     * Notify a specific user.
     * Enforces only guru/siswa recipients.
     */
    public static function notifyUser(User $user, array $payload): void
    {
        if (!in_array($user->role, ['guru', 'siswa'], true)) {
            return;
        }

        Notification::create([
            'user_id' => $user->id,
            'title' => $payload['title'] ?? 'Notifikasi',
            'message' => $payload['message'] ?? '',
            'type' => $payload['type'] ?? null,
            'icon' => $payload['icon'] ?? null,
            'related_model' => $payload['related_model'] ?? null,
            'related_id' => $payload['related_id'] ?? null,
            'action_url' => $payload['action_url'] ?? null,
            'is_read' => false,
        ]);
    }
}
