<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Scolarite;
use Illuminate\Http\Request;

class ScolariteController extends Controller
{
    public function index()
    {
        $scolarites = Scolarite::with('cycle', 'tranches')->paginate(15);
        return view('finance.scolarites.index', compact('scolarites'));
    }

    public function create()
    {
        return view('finance.scolarites.create', [
            'scolarite' => new Scolarite(),
            'cycles' => Cycle::orderBy('libelle')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Scolarite::create($this->validateData($request));
        return redirect()->route('finance.scolarites.index')->with('success', 'Frais de scolarité créés.');
    }

    public function edit(Scolarite $scolarite)
    {
        return view('finance.scolarites.edit', [
            'scolarite' => $scolarite,
            'cycles' => Cycle::orderBy('libelle')->get(),
        ]);
    }

    public function update(Request $request, Scolarite $scolarite)
    {
        $scolarite->update($this->validateData($request));
        return redirect()->route('finance.scolarites.index')->with('success', 'Frais mis à jour.');
    }

    public function destroy(Scolarite $scolarite)
    {
        $scolarite->delete();
        return back()->with('success', 'Frais supprimés.');
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
