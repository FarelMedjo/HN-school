<?php

namespace App\Http\Controllers\Scolarite;

use App\Http\Controllers\Admin\EmploiDuTempsController;
use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Appreciation;
use App\Models\Classe;
use App\Models\Convocation;
use App\Models\Eleve;
use App\Models\Frequente;
use App\Models\Presence;
use App\Models\Remarque;
use App\Models\Salle;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScolariteController extends Controller
{
    public function dashboard()
    {
        $annee = AnneeAcademique::where('actif', true)->first();

        $nbEleves   = Eleve::count();
        $nbClasses  = Classe::count();
        $nbInscrits = $annee
            ? Frequente::where('idAcademi', $annee->idAnnee)->distinct('matricule')->count('matricule')
            : 0;

        $absencesJour = Presence::whereDate('date', now()->toDateString())
            ->where('statut', 'absent')->count();

        $derniersInscrits = Eleve::orderByDesc('matricule')->take(6)->get();

        return view('scolarite.dashboard', compact(
            'annee', 'nbEleves', 'nbClasses', 'nbInscrits', 'absencesJour', 'derniersInscrits'
        ));
    }

    // ── Présences ──────────────────────────────────────────────────────────────

    public function presences(Request $request)
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

        return view('scolarite.presences.index', compact('salles', 'idSalle', 'date', 'eleves', 'presences'));
    }

    public function storePresences(Request $request)
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
            ->route('scolarite.presences.index', ['idSalle' => $data['idSalle'], 'date' => $data['date']])
            ->with('success', 'Pointage enregistré.');
    }

    public function presenceEleve(Eleve $eleve)
    {
        $presences = Presence::where('matricule', $eleve->matricule)
            ->orderByDesc('date')
            ->paginate(30);
        return view('scolarite.presences.eleve', compact('eleve', 'presences'));
    }

    // ── Emploi du temps (lecture seule) ─────────────────────────────────────────

    public function emploiDuTemps(Request $request)
    {
        $classes  = Classe::with('cycle')->orderBy('libelle')->get();
        $idClasse = $request->integer('idClasse') ?: $classes->first()?->idClasse;

        $grille = collect();
        $palette = collect();
        $classeLibelle = null;

        if ($idClasse) {
            $edt = EmploiDuTempsController::buildGrille($idClasse);
            $grille = $edt['grille'];
            $palette = $edt['palette'];
            $classeLibelle = optional($classes->firstWhere('idClasse', $idClasse))->libelle;
        }

        return view('scolarite.emploi-du-temps.index', compact('classes', 'idClasse', 'grille', 'palette', 'classeLibelle'));
    }

    // ── Bulletins ───────────────────────────────────────────────────────────────

    public function bulletins()
    {
        $eleves     = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $trimestres = Trimestre::with('annee')->get();
        return view('scolarite.bulletins.index', compact('eleves', 'trimestres'));
    }

    public function bulletinShow(Eleve $eleve, Trimestre $trimestre)
    {
        return app(\App\Http\Controllers\Admin\BulletinController::class)->show($eleve, $trimestre);
    }

    // ── Convocations ─────────────────────────────────────────────────────────────

    public function convocations(Request $request)
    {
        $matricule = $request->integer('matricule') ?: null;

        $convocations = Convocation::with(['eleve', 'auteur'])
            ->when($matricule, fn ($q) => $q->where('matricule', $matricule))
            ->orderByDesc('dateRdv')->paginate(20)->withQueryString();

        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();

        return view('scolarite.convocations.index', compact('convocations', 'eleves', 'matricule'));
    }

    public function createConvocation()
    {
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();

        return view('scolarite.convocations.create', compact('eleves'));
    }

    public function storeConvocation(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'objet'     => ['required', 'string', 'max:255'],
            'motif'     => ['nullable', 'string'],
            'dateRdv'   => ['required', 'date'],
            'lieu'      => ['nullable', 'string', 'max:255'],
        ]);
        $data['idAuteur'] = auth()->id();

        $convocation = Convocation::create($data);

        return redirect()->route('scolarite.convocations.show', $convocation)->with('success', 'Convocation enregistrée.');
    }

    public function convocationShow(Convocation $convocation)
    {
        return app(\App\Http\Controllers\Admin\ConvocationController::class)->show($convocation);
    }

    public function destroyConvocation(Convocation $convocation)
    {
        $convocation->delete();

        return redirect()->route('scolarite.convocations.index')->with('success', 'Convocation supprimée.');
    }

    // ── Appréciations ────────────────────────────────────────────────────────────

    public function appreciations(Request $request)
    {
        $idTrimes = $request->integer('idTrimes') ?: null;

        $appreciations = Appreciation::with(['eleve', 'trimestre.annee'])
            ->when($idTrimes, fn ($q) => $q->where('idTrimes', $idTrimes))
            ->orderByDesc('updated_at')->paginate(20)->withQueryString();

        $eleves     = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $trimestres = Trimestre::with('annee')->get();

        return view('scolarite.appreciations.index', compact('appreciations', 'eleves', 'trimestres', 'idTrimes'));
    }

    public function storeAppreciation(Request $request)
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

        return redirect()->route('scolarite.appreciations.index')->with('success', 'Appréciation enregistrée.');
    }

    public function destroyAppreciation(Appreciation $appreciation)
    {
        $appreciation->delete();

        return redirect()->route('scolarite.appreciations.index')->with('success', 'Appréciation supprimée.');
    }

    // ── Journal / Remarques ──────────────────────────────────────────────────────

    public function remarques(Request $request)
    {
        $matricule = $request->integer('matricule') ?: null;

        $remarques = Remarque::with(['eleve', 'auteur'])
            ->when($matricule, fn ($q) => $q->where('matricule', $matricule))
            ->orderByDesc('date')->orderByDesc('idRemarque')->paginate(20)->withQueryString();

        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();

        return view('scolarite.remarques.index', compact('remarques', 'eleves', 'matricule'));
    }

    public function storeRemarque(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'date'      => ['required', 'date'],
            'categorie' => ['required', Rule::in(array_keys(Remarque::CATEGORIES))],
            'contenu'   => ['required', 'string'],
        ]);
        $data['idAuteur'] = auth()->id();

        Remarque::create($data);

        return redirect()->route('scolarite.remarques.index', ['matricule' => $data['matricule']])->with('success', 'Remarque enregistrée.');
    }

    public function destroyRemarque(Remarque $remarque)
    {
        $remarque->delete();

        return redirect()->route('scolarite.remarques.index')->with('success', 'Remarque supprimée.');
    }
}
