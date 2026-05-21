<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Scolarite;
use Illuminate\Http\Request;

class ScolariteController extends Controller
{
    public function index()
    {
        $scolarites = Scolarite::with('cycle', 'tranches')->paginate(15);
        return view('admin.scolarites.index', compact('scolarites'));
    }

    public function create()
    {
        return view('admin.scolarites.create', [
            'scolarite' => new Scolarite(),
            'cycles' => Cycle::orderBy('libelle')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Scolarite::create($this->validateData($request));
        return redirect()->route('admin.scolarites.index')->with('success', 'Scolarité créée.');
    }

    public function edit(Scolarite $scolarite)
    {
        return view('admin.scolarites.edit', [
            'scolarite' => $scolarite,
            'cycles' => Cycle::orderBy('libelle')->get(),
        ]);
    }

    public function update(Request $request, Scolarite $scolarite)
    {
        $scolarite->update($this->validateData($request));
        return redirect()->route('admin.scolarites.index')->with('success', 'Scolarité mise à jour.');
    }

    public function destroy(Scolarite $scolarite)
    {
        $scolarite->delete();
        return back()->with('success', 'Scolarité supprimée.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'inscription' => ['required', 'numeric', 'min:0'],
            'pension' => ['required', 'numeric', 'min:0'],
            'nbreTranche' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'idCycle' => ['nullable', 'exists:cycles,idCycle'],
        ]);
    }
}
