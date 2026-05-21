<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::with('classe')->orderBy('libelle')->paginate(15);
        return view('admin.salles.index', compact('salles'));
    }

    public function create()
    {
        return view('admin.salles.create', [
            'salle' => new Salle(),
            'classes' => Classe::orderBy('libelle')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Salle::create($this->validateData($request));
        return redirect()->route('admin.salles.index')->with('success', 'Salle créée.');
    }

    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', [
            'salle' => $salle,
            'classes' => Classe::orderBy('libelle')->get(),
        ]);
    }

    public function update(Request $request, Salle $salle)
    {
        $salle->update($this->validateData($request));
        return redirect()->route('admin.salles.index')->with('success', 'Salle mise à jour.');
    }

    public function destroy(Salle $salle)
    {
        $salle->delete();
        return redirect()->route('admin.salles.index')->with('success', 'Salle supprimée.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'libelle' => ['required', 'string', 'max:30'],
            'position' => ['nullable', 'string', 'max:100'],
            'surface' => ['nullable', 'string', 'max:30'],
            'idClasse' => ['nullable', 'exists:classes,idClasse'],
            'actif' => ['nullable', 'boolean'],
        ]) + ['actif' => $r->boolean('actif', true)];
    }
}
