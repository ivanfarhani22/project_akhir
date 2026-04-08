<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\EClass;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];
        $user = auth()->user();

        // Search Materials
        if ($user->role === 'admin_elearning') {
            $materials = Material::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            foreach ($materials as $material) {
                $results[] = [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => 'Materi - ' . $material->eClass->name,
                    'url' => route('admin.materials.show', $material),
                    'icon' => 'fas fa-book',
                    'type' => 'material'
                ];
            }

            // Search Assignments
            $assignments = Assignment::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            foreach ($assignments as $assignment) {
                $results[] = [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => 'Tugas - ' . $assignment->eClass->name,
                    'url' => route('admin.assignments.show', $assignment),
                    'icon' => 'fas fa-tasks',
                    'type' => 'assignment'
                ];
            }

            // Search Classes
            $classes = EClass::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(3)
                ->get();

            foreach ($classes as $class) {
                $results[] = [
                    'id' => $class->id,
                    'title' => $class->name,
                    'description' => 'Kelas - ' . ($class->description ?? 'Tanpa deskripsi'),
                    'url' => route('admin.classes.show', $class),
                    'icon' => 'fas fa-chalkboard',
                    'type' => 'class'
                ];
            }

            // Search Users
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(3)
                ->get();

            foreach ($users as $searchUser) {
                $results[] = [
                    'id' => $searchUser->id,
                    'title' => $searchUser->name,
                    'description' => 'Pengguna - ' . ucfirst(str_replace('_', ' ', $searchUser->role)),
                    'url' => route('admin.users.show', $searchUser),
                    'icon' => 'fas fa-user',
                    'type' => 'user'
                ];
            }
        }

        return response()->json([
            'results' => array_slice($results, 0, 10)
        ]);
    }
}
