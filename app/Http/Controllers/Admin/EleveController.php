<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Eleve;
use App\Models\Frequente;
use App\Models\Salle;
use App\Models\VilleNaissance;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $eleves = Eleve::with('villeNaissance')
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('nom', 'like', "%$q%")
                    ->orWhere('prenom', 'like', "%$q%");
            }))
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString();
        return view('admin.eleves.index', compact('eleves', 'q'));
    }

    public function create()
    {
        return view('admin.eleves.create', [
            'eleve' => new Eleve(),
            'villes' => VilleNaissance::orderBy('libelle')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Eleve::create($this->validateData($request));
        return redirect()->route('admin.eleves.index')->with('success', 'Élève créé.');
    }

    public function show(Eleve $eleve)
    {
        $eleve->load('villeNaissance', 'frequentations.salle.classe', 'paiements.mode');
        $salles = Salle::with('classe')->orderBy('libelle')->get();
        $annees = AnneeAcademique::orderByDesc('idAnnee')->get();
        return view('admin.eleves.show', compact('eleve', 'salles', 'annees'));
    }

    public function edit(Eleve $eleve)
    {
        return view('admin.eleves.edit', [
            'eleve' => $eleve,
            'villes' => VilleNaissance::orderBy('libelle')->get(),
        ]);
    }

    public function update(Request $request, Eleve $eleve)
    {
        $eleve->update($this->validateData($request));
        return redirect()->route('admin.eleves.index')->with('success', 'Élève mis à jour.');
    }

    public function destroy(Eleve $eleve)
    {
        $eleve->delete();
        return redirect()->route('admin.eleves.index')->with('success', 'Élève supprimé.');
    }

    public function affecter(Request $request, Eleve $eleve)
    {
        $data = $request->validate([
            'idSalle' => ['required', 'exists:salles,idSalle'],
            'idAcademi' => ['required', 'exists:annee_academiques,idAnnee'],
            'commentaire' => ['nullable', 'string', 'max:255'],
        ]);
        Frequente::updateOrCreate(
            ['matricule' => $eleve->matricule, 'idAcademi' => $data['idAcademi']],
            ['idSalle' => $data['idSalle'], 'commentaire' => $data['commentaire'] ?? null]
        );
        return back()->with('success', 'Affectation enregistrée.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'nom' => ['required', 'string', 'max:60'],
            'prenom' => ['required', 'string', 'max:60'],
            'dateNaissance' => ['nullable', 'date'],
            'lieuNaissance' => ['nullable', 'string', 'max:30'],
            'sexe' => ['nullable', 'integer', 'in:0,1'],
            'langue' => ['nullable', 'string', 'max:30'],
            'idVilleNaissance' => ['nullable', 'exists:ville_naissances,idVille'],
        ]);
    }
}
