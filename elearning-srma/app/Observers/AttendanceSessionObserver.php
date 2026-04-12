<?php

namespace App\Observers;

use App\Models\AttendanceSession;
use App\Services\NotificationService;

class AttendanceSessionObserver
{
    /**
     * Handle the AttendanceSession "created" event.
     */
    public function created(AttendanceSession $session): void
    {
        $class = $session->classSubject?->eClass;
        if (!$class) return;

        NotificationService::notifyClassStudents($class, [
            'title' => 'Presensi Dibuka',
            'message' => "Presensi {$class->name} dibuka untuk tanggal " . $session->attendance_date->format('d M Y'),
            'type' => 'attendance',
            'icon' => 'fas fa-clipboard-list',
            'related_model' => AttendanceSession::class,
            'related_id' => $session->id,
            // Students go to attendance page per classSubject
            'action_url' => route('siswa.attendance.show', $session->class_subject_id),
        ]);
    }

    /**
     * Handle the AttendanceSession "updated" event.
     */
    public function updated(AttendanceSession $session): void
    {
        $class = $session->classSubject?->eClass;
        if (!$class) return;

        NotificationService::notifyClassStudents($class, [
            'title' => 'Presensi Diperbarui',
            'message' => "Update presensi untuk kelas {$class->name}",
            'type' => 'attendance',
            'icon' => 'fas fa-sync-alt',
            'related_model' => AttendanceSession::class,
            'related_id' => $session->id,
            'action_url' => route('siswa.attendance.show', $session->class_subject_id),
        ]);
    }

    /**
     * Handle the AttendanceSession "deleted" event.
     */
    public function deleted(AttendanceSession $session): void
    {
        $class = $session->classSubject?->eClass;
        if (!$class) return;

        NotificationService::notifyClassStudents($class, [
            'title' => 'Presensi Dihapus',
            'message' => "Presensi untuk kelas {$class->name} telah dihapus",
            'type' => 'attendance',
            'icon' => 'fas fa-trash',
            'related_model' => AttendanceSession::class,
            'related_id' => $session->id,
        ]);
    }
}
