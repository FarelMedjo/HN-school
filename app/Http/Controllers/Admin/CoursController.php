<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\Personne;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index()
    {
        $cours = Cours::with('classe', 'enseignant')->orderBy('libelle')->paginate(15);
        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        return view('admin.cours.create', [
            'cours' => new Cours(),
            'classes' => Classe::orderBy('libelle')->get(),
            'enseignants' => Personne::where('typePersonne', 2)->orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Cours::create($this->validateData($request));
        return redirect()->route('admin.cours.index')->with('success', 'Cours créé.');
    }

    public function edit(Cours $cour)
    {
        return view('admin.cours.edit', [
            'cours' => $cour,
            'classes' => Classe::orderBy('libelle')->get(),
            'enseignants' => Personne::where('typePersonne', 2)->orderBy('nom')->get(),
        ]);
    }

    public function update(Request $request, Cours $cour)
    {
        $cour->update($this->validateData($request));
        return redirect()->route('admin.cours.index')->with('success', 'Cours mis à jour.');
    }

    public function destroy(Cours $cour)
    {
        $cour->delete();
        return redirect()->route('admin.cours.index')->with('success', 'Cours supprimé.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'numeric'],
            'coefficient' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
            'idClasse' => ['nullable', 'exists:classes,idClasse'],
            'idPers' => ['nullable', 'exists:personnes,idPers'],
        ]);
    }
}
