<?php

namespace App\Http\Controllers\Scolarite;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    public function index()
    {
        $cycles = Cycle::withCount('classes')->orderBy('libelle')->paginate(15);
        return view('scolarite.cycles.index', compact('cycles'));
    }

    public function create()
    {
        return view('scolarite.cycles.create', ['cycle' => new Cycle()]);
    }

    public function store(Request $request)
    {
        Cycle::create($this->validateData($request));
        return redirect()->route('scolarite.cycles.index')->with('success', 'Cycle créé.');
    }

    public function edit(Cycle $cycle)
    {
        return view('scolarite.cycles.edit', compact('cycle'));
    }

    public function update(Request $request, Cycle $cycle)
    {
        $cycle->update($this->validateData($request));
        return redirect()->route('scolarite.cycles.index')->with('success', 'Cycle mis à jour.');
    }

    public function destroy(Cycle $cycle)
    {
        $cycle->delete();
        return redirect()->route('scolarite.cycles.index')->with('success', 'Cycle supprimé.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
