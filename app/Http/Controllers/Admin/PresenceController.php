<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Eleve;
use App\Models\Frequente;
use App\Models\Presence;
use App\Models\Salle;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    /**
     * Page de pointage : choix salle + date, et pointage en lot.
     */
    public function index(Request $request)
    {
        $salles = Salle::with('classe')->orderBy('libelle')->get();
        $idSalle = $request->integer('idSalle') ?: $salles->first()?->idSalle;
        $date = $request->date('date') ?? now()->toDateString();
        if ($date instanceof \DateTimeInterface) {
            $date = $date->format('Y-m-d');
        }

        $eleves = collect();
        $presences = collect();

        if ($idSalle) {
            // Élèves rattachés à la salle pour l'année active
            $anneeActive = AnneeAcademique::where('actif', true)->first();
            $eleveIds = Frequente::where('idSalle', $idSalle)
                ->when($anneeActive, fn ($q) => $q->where('idAcademi', $anneeActive->idAnnee))
                ->pluck('matricule');
            $eleves = Eleve::whereIn('matricule', $eleveIds)->orderBy('nom')->get();

            $presences = Presence::where('idSalle', $idSalle)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('matricule');
        }

        return view('admin.presences.index', compact('salles', 'idSalle', 'date', 'eleves', 'presences'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idSalle' => ['required', 'exists:salles,idSalle'],
            'date' => ['required', 'date'],
            'statuts' => ['required', 'array'],
            'statuts.*' => ['required', 'in:present,absent,retard,justifie'],
            'motifs' => ['nullable', 'array'],
        ]);

        foreach ($data['statuts'] as $matricule => $statut) {
            Presence::updateOrCreate(
                [
                    'matricule' => $matricule,
                    'idSalle' => $data['idSalle'],
                    'date' => $data['date'],
                ],
                [
                    'statut' => $statut,
                    'motif' => $data['motifs'][$matricule] ?? null,
                    'idPers' => optional(auth()->user())->id,
                ]
            );
        }

        return redirect()
            ->route('admin.presences.index', ['idSalle' => $data['idSalle'], 'date' => $data['date']])
            ->with('success', 'Pointage enregistré.');
    }

    /**
     * Historique des absences d'un élève.
     */
    public function eleve(Eleve $eleve)
    {
        $presences = Presence::where('matricule', $eleve->matricule)
            ->orderByDesc('date')
            ->paginate(30);
        return view('admin.presences.eleve', compact('eleve', 'presences'));
    }
}
