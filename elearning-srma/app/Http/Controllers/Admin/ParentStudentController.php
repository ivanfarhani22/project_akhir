<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ParentStudentController extends Controller
{
    public function index(Request $request)
    {
        $parents = User::query()
            ->where('role', 'orang_tua')
            ->with(['children' => fn ($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->paginate(20);

        $students = User::query()
            ->where('role', 'siswa')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.parent-students.index', compact('parents', 'students'));
    }

    public function update(Request $request, User $parent)
    {
        abort_if($parent->role !== 'orang_tua', 404);

        $validated = $request->validate([
            'student_ids'   => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $studentIds = collect($validated['student_ids'] ?? [])
            ->unique()
            ->values();

        // Ensure all selected are siswa
        $countSiswa = User::whereIn('id', $studentIds)->where('role', 'siswa')->count();
        abort_if($countSiswa !== $studentIds->count(), 422, 'Pilihan siswa tidak valid');

        $parent->children()->sync($studentIds);

        return back()->with('success', 'Relasi orang tua → siswa berhasil disimpan.');
    }
}
