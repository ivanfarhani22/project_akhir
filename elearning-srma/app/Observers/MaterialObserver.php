<?php

namespace App\Observers;

use App\Models\Material;
use App\Models\Notification;

class MaterialObserver
{
    /**
     * Handle the Material "created" event.
     */
    public function created(Material $material): void
    {
        // Notify all admin users
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Materi Baru Ditambahkan',
                'message' => "Materi \"{$material->title}\" telah ditambahkan ke kelas {$material->eClass->name}",
                'type' => 'material',
                'icon' => 'fas fa-book',
                'related_model' => Material::class,
                'related_id' => $material->id,
                'action_url' => route('admin.materials.show', $material),
            ]);
        }
    }

    /**
     * Handle the Material "updated" event.
     */
    public function updated(Material $material): void
    {
        // Notify all admin users when material is updated
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Materi Diperbarui',
                'message' => "Materi \"{$material->title}\" telah diperbarui",
                'type' => 'material',
                'icon' => 'fas fa-sync-alt',
                'related_model' => Material::class,
                'related_id' => $material->id,
                'action_url' => route('admin.materials.show', $material),
            ]);
        }
    }

    /**
     * Handle the Material "deleted" event.
     */
    public function deleted(Material $material): void
    {
        // Notify all admin users
        $admins = \App\Models\User::where('role', 'admin_elearning')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Materi Dihapus',
                'message' => "Materi \"{$material->title}\" telah dihapus dari sistem",
                'type' => 'material',
                'icon' => 'fas fa-trash',
                'related_model' => Material::class,
                'related_id' => $material->id,
            ]);
        }
    }
}
