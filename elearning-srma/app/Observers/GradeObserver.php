<?php

namespace App\Observers;

use App\Models\Grade;
use App\Services\NotificationService;

class GradeObserver
{
    /**
     * Handle the Grade "created" event.
     */
    public function created(Grade $grade): void
    {
        if (!$grade->student) return;

        NotificationService::notifyUser($grade->student, [
            'title' => 'Nilai Baru',
            'message' => "Nilai baru telah ditambahkan untuk Anda",
            'type' => 'grade',
            'icon' => 'fas fa-star',
            'related_model' => Grade::class,
            'related_id' => $grade->id,
            'action_url' => route('siswa.grades.index'),
        ]);
    }

    /**
     * Handle the Grade "updated" event.
     */
    public function updated(Grade $grade): void
    {
        if (!$grade->student) return;

        NotificationService::notifyUser($grade->student, [
            'title' => 'Nilai Diperbarui',
            'message' => "Nilai Anda telah diperbarui",
            'type' => 'grade',
            'icon' => 'fas fa-sync-alt',
            'related_model' => Grade::class,
            'related_id' => $grade->id,
            'action_url' => route('siswa.grades.index'),
        ]);
    }
}
