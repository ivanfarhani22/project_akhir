<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Agenda::with('user')->latest();
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }
        
        $agendas = $query->paginate(10);
        
        return view('admin.agendas.index', compact('agendas'));
    }

    public function create()
    {
        return view('admin.agendas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $validated['user_id'] = auth()->id();

        $agenda = Agenda::create($validated);
        
        ActivityLog::log('create', "Membuat agenda: {$agenda->title}", Agenda::class, $agenda->id);

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dibuat.');
    }

    public function edit(Agenda $agenda)
    {
        return view('admin.agendas.edit', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $oldValues = $agenda->toArray();

        $agenda->update($validated);
        
        ActivityLog::log('update', "Mengupdate agenda: {$agenda->title}", Agenda::class, $agenda->id, $oldValues, $agenda->toArray());

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil diupdate.');
    }

    public function destroy(Agenda $agenda)
    {
        $title = $agenda->title;
        $agenda->delete();
        
        ActivityLog::log('delete', "Menghapus agenda: {$title}", Agenda::class);

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}
