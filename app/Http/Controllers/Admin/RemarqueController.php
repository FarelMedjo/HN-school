<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Remarque;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RemarqueController extends Controller
{
    public function index(Request $request)
    {
        $matricule = $request->integer('matricule') ?: null;

        $remarques = Remarque::with(['eleve', 'auteur'])
            ->when($matricule, fn ($q) => $q->where('matricule', $matricule))
            ->orderByDesc('date')
            ->orderByDesc('idRemarque')
            ->paginate(20)
            ->withQueryString();

        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();

        return view('admin.remarques.index', compact('remarques', 'eleves', 'matricule'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'date'      => ['required', 'date'],
            'categorie' => ['required', Rule::in(array_keys(Remarque::CATEGORIES))],
            'contenu'   => ['required', 'string'],
        ]);
        $data['idAuteur'] = auth()->id();

        Remarque::create($data);

        return redirect()
            ->route('admin.remarques.index', ['matricule' => $data['matricule']])
            ->with('success', 'Remarque enregistrée.');
    }

    public function destroy(Remarque $remarque)
    {
        $remarque->delete();

        return redirect()
            ->route('admin.remarques.index')
            ->with('success', 'Remarque supprimée.');
    }
}
