<?php

namespace App\Observers;

use App\Models\Assignment;
use App\Models\Notification;

class AssignmentObserver
{
    /**
     * Handle the Assignment "created" event.
     */
    public function created(Assignment $assignment): void
    {
        // Notify all admin users
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Tugas Baru Dibuat',
                'message' => "Tugas \"{$assignment->title}\" telah dibuat untuk kelas {$assignment->eClass->name}",
                'type' => 'assignment',
                'icon' => 'fas fa-tasks',
                'related_model' => Assignment::class,
                'related_id' => $assignment->id,
                'action_url' => route('admin.assignments.show', $assignment),
            ]);
        }
    }

    /**
     * Handle the Assignment "updated" event.
     */
    public function updated(Assignment $assignment): void
    {
        // Notify all admin users when assignment is updated
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Tugas Diperbarui',
                'message' => "Tugas \"{$assignment->title}\" telah diperbarui",
                'type' => 'assignment',
                'icon' => 'fas fa-sync-alt',
                'related_model' => Assignment::class,
                'related_id' => $assignment->id,
                'action_url' => route('admin.assignments.show', $assignment),
            ]);
        }
    }

    /**
     * Handle the Assignment "deleted" event.
     */
    public function deleted(Assignment $assignment): void
    {
        // Notify all admin users
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Tugas Dihapus',
                'message' => "Tugas \"{$assignment->title}\" telah dihapus dari sistem",
                'type' => 'assignment',
                'icon' => 'fas fa-trash',
                'related_model' => Assignment::class,
                'related_id' => $assignment->id,
            ]);
        }
    }
}
