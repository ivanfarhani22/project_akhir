<?php

namespace App\Observers;

use App\Models\Material;
use App\Services\NotificationService;

class MaterialObserver
{
    /**
     * Handle the Material "created" event.
     */
    public function created(Material $material): void
    {
        // Guru -> Siswa: notify students in the class
        NotificationService::notifyClassStudents($material->eClass, [
            'title' => 'Materi Baru',
            'message' => "Materi \"{$material->title}\" ditambahkan pada kelas {$material->eClass->name}",
            'type' => 'material',
            'icon' => 'fas fa-book',
            'related_model' => Material::class,
            'related_id' => $material->id,
            'action_url' => route('siswa.subjects.show', $material->e_class_id),
        ]);
    }

    /**
     * Handle the Material "updated" event.
     */
    public function updated(Material $material): void
    {
        NotificationService::notifyClassStudents($material->eClass, [
            'title' => 'Materi Diperbarui',
            'message' => "Materi \"{$material->title}\" diperbarui pada kelas {$material->eClass->name}",
            'type' => 'material',
            'icon' => 'fas fa-sync-alt',
            'related_model' => Material::class,
            'related_id' => $material->id,
            'action_url' => route('siswa.subjects.show', $material->e_class_id),
        ]);
    }

    /**
     * Handle the Material "deleted" event.
     */
    public function deleted(Material $material): void
    {
        NotificationService::notifyClassStudents($material->eClass, [
            'title' => 'Materi Dihapus',
            'message' => "Materi \"{$material->title}\" dihapus dari kelas {$material->eClass->name}",
            'type' => 'material',
            'icon' => 'fas fa-trash',
            'related_model' => Material::class,
            'related_id' => $material->id,
        ]);
    }
}
