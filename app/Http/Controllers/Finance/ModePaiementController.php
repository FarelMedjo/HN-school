<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\ModePaiement;
use Illuminate\Http\Request;

class ModePaiementController extends Controller
{
    public function index()
    {
        $modes = ModePaiement::orderBy('libelle')->paginate(15);
        return view('finance.modes.index', compact('modes'));
    }

    public function create()
    {
        return view('finance.modes.create', ['mode' => new ModePaiement()]);
    }

    public function store(Request $request)
    {
        ModePaiement::create($this->validateData($request));
        return redirect()->route('finance.modes.index')->with('success', 'Mode de paiement créé.');
    }

    public function edit(ModePaiement $mode)
    {
        return view('finance.modes.edit', compact('mode'));
    }

    public function update(Request $request, ModePaiement $mode)
    {
        $mode->update($this->validateData($request));
        return redirect()->route('finance.modes.index')->with('success', 'Mode de paiement mis à jour.');
    }

    public function destroy(ModePaiement $mode)
    {
        $mode->delete();
        return back()->with('success', 'Mode de paiement supprimé.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'libelle' => ['required', 'string', 'max:100'],
            'information' => ['nullable', 'string'],
            'actif' => ['nullable', 'boolean'],
        ]);
    }
}
