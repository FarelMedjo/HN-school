<?php

namespace App\Http\Controllers\Scolarite;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use Illuminate\Http\Request;

class AnneeAcademiqueController extends Controller
{
    public function index()
    {
        $annees = AnneeAcademique::orderByDesc('idAnnee')->paginate(15);
        return view('scolarite.annees.index', compact('annees'));
    }

    public function create()
    {
        return view('scolarite.annees.create', ['annee' => new AnneeAcademique()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if (!empty($data['actif'])) {
            AnneeAcademique::query()->update(['actif' => false]);
        }
        AnneeAcademique::create($data);
        return redirect()->route('scolarite.annees.index')->with('success', 'Année créée.');
    }

    public function edit(AnneeAcademique $annee)
    {
        return view('scolarite.annees.edit', compact('annee'));
    }

    public function update(Request $request, AnneeAcademique $annee)
    {
        $data = $this->validateData($request);
        if (!empty($data['actif'])) {
            AnneeAcademique::where('idAnnee', '!=', $annee->idAnnee)->update(['actif' => false]);
        }
        $annee->update($data);
        return redirect()->route('scolarite.annees.index')->with('success', 'Année mise à jour.');
    }

    public function destroy(AnneeAcademique $annee)
    {
        $annee->delete();
        return redirect()->route('scolarite.annees.index')->with('success', 'Année supprimée.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'libelle' => ['required', 'string', 'max:200'],
            'periode' => ['nullable', 'string', 'max:255'],
            'actif' => ['nullable', 'boolean'],
        ]) + ['actif' => $r->boolean('actif')];
    }
}
