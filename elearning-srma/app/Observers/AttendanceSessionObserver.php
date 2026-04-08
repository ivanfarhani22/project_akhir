<?php

namespace App\Observers;

use App\Models\AttendanceSession;
use App\Models\Notification;

class AttendanceSessionObserver
{
    /**
     * Handle the AttendanceSession "created" event.
     */
    public function created(AttendanceSession $session): void
    {
        // Notify all admin users
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Presensi Baru Dicatat',
                'message' => "Presensi untuk kelas {$session->classSubject->eClass->name} telah dicatat pada " . $session->attendance_date->format('d M Y'),
                'type' => 'attendance',
                'icon' => 'fas fa-clipboard-list',
                'related_model' => AttendanceSession::class,
                'related_id' => $session->id,
                'action_url' => route('admin.attendance.show', $session),
            ]);
        }
    }

    /**
     * Handle the AttendanceSession "updated" event.
     */
    public function updated(AttendanceSession $session): void
    {
        // Notify all admin users when attendance is updated
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Presensi Diperbarui',
                'message' => "Presensi untuk kelas {$session->classSubject->eClass->name} telah diperbarui",
                'type' => 'attendance',
                'icon' => 'fas fa-sync-alt',
                'related_model' => AttendanceSession::class,
                'related_id' => $session->id,
                'action_url' => route('admin.attendance.show', $session),
            ]);
        }
    }

    /**
     * Handle the AttendanceSession "deleted" event.
     */
    public function deleted(AttendanceSession $session): void
    {
        // Notify all admin users
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Presensi Dihapus',
                'message' => "Presensi untuk kelas {$session->classSubject->eClass->name} telah dihapus",
                'type' => 'attendance',
                'icon' => 'fas fa-trash',
                'related_model' => AttendanceSession::class,
                'related_id' => $session->id,
            ]);
        }
    }
}
