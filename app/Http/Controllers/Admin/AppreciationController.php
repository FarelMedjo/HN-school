<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appreciation;
use App\Models\Eleve;
use App\Models\Trimestre;
use Illuminate\Http\Request;

class AppreciationController extends Controller
{
    public function index(Request $request)
    {
        $idTrimes = $request->integer('idTrimes') ?: null;

        $appreciations = Appreciation::with(['eleve', 'trimestre.annee', 'auteur'])
            ->when($idTrimes, fn ($q) => $q->where('idTrimes', $idTrimes))
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        $eleves     = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $trimestres = Trimestre::with('annee')->get();

        return view('admin.appreciations.index', compact('appreciations', 'eleves', 'trimestres', 'idTrimes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'idTrimes'  => ['required', 'exists:trimestres,idTrimes'],
            'contenu'   => ['required', 'string'],
        ]);

        Appreciation::updateOrCreate(
            ['matricule' => $data['matricule'], 'idTrimes' => $data['idTrimes']],
            ['contenu' => $data['contenu'], 'idAuteur' => auth()->id()]
        );

        return redirect()
            ->route('admin.appreciations.index')
            ->with('success', 'Appréciation enregistrée.');
    }

    public function destroy(Appreciation $appreciation)
    {
        $appreciation->delete();

        return redirect()
            ->route('admin.appreciations.index')
            ->with('success', 'Appréciation supprimée.');
    }
}
