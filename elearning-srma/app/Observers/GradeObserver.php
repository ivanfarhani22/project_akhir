<?php

namespace App\Observers;

use App\Models\Grade;
use App\Models\Notification;

class GradeObserver
{
    /**
     * Handle the Grade "created" event.
     */
    public function created(Grade $grade): void
    {
        // Notify admin
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Nilai Baru Ditambahkan',
                'message' => "Nilai untuk siswa {$grade->student->name} telah ditambahkan",
                'type' => 'grade',
                'icon' => 'fas fa-star',
                'related_model' => Grade::class,
                'related_id' => $grade->id,
                'action_url' => route('admin.grades.index'),
            ]);
        }
    }

    /**
     * Handle the Grade "updated" event.
     */
    public function updated(Grade $grade): void
    {
        // Notify admin when grade is updated
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Nilai Diperbarui',
                'message' => "Nilai untuk siswa {$grade->student->name} telah diperbarui",
                'type' => 'grade',
                'icon' => 'fas fa-sync-alt',
                'related_model' => Grade::class,
                'related_id' => $grade->id,
                'action_url' => route('admin.grades.index'),
            ]);
        }
    }
}
