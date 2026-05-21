<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    public function index()
    {
        $cycles = Cycle::withCount('classes')->orderBy('libelle')->paginate(15);
        return view('admin.cycles.index', compact('cycles'));
    }

    public function create()
    {
        return view('admin.cycles.create', ['cycle' => new Cycle()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Cycle::create($data);
        return redirect()->route('admin.cycles.index')->with('success', 'Cycle créé.');
    }

    public function edit(Cycle $cycle)
    {
        return view('admin.cycles.edit', compact('cycle'));
    }

    public function update(Request $request, Cycle $cycle)
    {
        $cycle->update($this->validateData($request));
        return redirect()->route('admin.cycles.index')->with('success', 'Cycle mis à jour.');
    }

    public function destroy(Cycle $cycle)
    {
        $cycle->delete();
        return redirect()->route('admin.cycles.index')->with('success', 'Cycle supprimé.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
