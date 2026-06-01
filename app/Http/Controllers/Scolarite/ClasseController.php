<?php

namespace App\Http\Controllers\Scolarite;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Cycle;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::with('cycle')->orderBy('libelle')->paginate(15);
        return view('scolarite.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('scolarite.classes.create', [
            'classe' => new Classe(),
            'cycles' => Cycle::orderBy('libelle')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Classe::create($this->validateData($request));
        return redirect()->route('scolarite.classes.index')->with('success', 'Classe créée.');
    }

    public function edit(Classe $classe)
    {
        return view('scolarite.classes.edit', [
            'classe' => $classe,
            'cycles' => Cycle::orderBy('libelle')->get(),
        ]);
    }

    public function update(Request $request, Classe $classe)
    {
        $classe->update($this->validateData($request));
        return redirect()->route('scolarite.classes.index')->with('success', 'Classe mise à jour.');
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('scolarite.classes.index')->with('success', 'Classe supprimée.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'libelle' => ['required', 'string', 'max:100'],
            'idCycle' => ['nullable', 'exists:cycles,idCycle'],
            'section' => ['required', 'in:francophone,anglophone,bilingue'],
        ]);
    }
}
