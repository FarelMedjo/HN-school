<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Eleve;
use App\Models\ModePaiement;
use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::with('eleve', 'mode', 'annee')
            ->orderByDesc('datePaie')
            ->paginate(15);
        return view('finance.paiements.index', compact('paiements'));
    }

    public function create()
    {
        return view('finance.paiements.create', [
            'paiement' => new Paiement(),
            'eleves' => Eleve::orderBy('nom')->get(),
            'modes' => ModePaiement::orderBy('libelle')->get(),
            'annees' => AnneeAcademique::orderByDesc('idAnnee')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Paiement::create($this->validateData($request));
        return redirect()->route('finance.paiements.index')->with('success', 'Paiement enregistré.');
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        return back()->with('success', 'Paiement supprimé.');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'idAca' => ['required', 'exists:annee_academiques,idAnnee'],
            'montant' => ['required', 'numeric', 'min:0'],
            'idMode' => ['nullable', 'exists:mode_paiements,idMode'],
            'datePaie' => ['required', 'date'],
            'commentaire' => ['nullable', 'string', 'max:255'],
            'operation_ID' => ['nullable', 'string', 'max:30'],
        ]);
    }
}
