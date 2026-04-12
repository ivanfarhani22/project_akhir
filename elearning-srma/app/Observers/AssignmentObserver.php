<?php

namespace App\Observers;

use App\Models\Assignment;
use App\Services\NotificationService;

class AssignmentObserver
{
    /**
     * Handle the Assignment "created" event.
     */
    public function created(Assignment $assignment): void
    {
        NotificationService::notifyClassStudents($assignment->eClass, [
            'title' => 'Tugas Baru',
            'message' => "Tugas \"{$assignment->title}\" dibuat untuk kelas {$assignment->eClass->name}",
            'type' => 'assignment',
            'icon' => 'fas fa-tasks',
            'related_model' => Assignment::class,
            'related_id' => $assignment->id,
            'action_url' => route('siswa.assignments.show', $assignment),
        ]);
    }

    /**
     * Handle the Assignment "updated" event.
     */
    public function updated(Assignment $assignment): void
    {
        NotificationService::notifyClassStudents($assignment->eClass, [
            'title' => 'Tugas Diperbarui',
            'message' => "Tugas \"{$assignment->title}\" diperbarui",
            'type' => 'assignment',
            'icon' => 'fas fa-sync-alt',
            'related_model' => Assignment::class,
            'related_id' => $assignment->id,
            'action_url' => route('siswa.assignments.show', $assignment),
        ]);
    }

    /**
     * Handle the Assignment "deleted" event.
     */
    public function deleted(Assignment $assignment): void
    {
        NotificationService::notifyClassStudents($assignment->eClass, [
            'title' => 'Tugas Dihapus',
            'message' => "Tugas \"{$assignment->title}\" dihapus dari kelas {$assignment->eClass->name}",
            'type' => 'assignment',
            'icon' => 'fas fa-trash',
            'related_model' => Assignment::class,
            'related_id' => $assignment->id,
        ]);
    }
}
