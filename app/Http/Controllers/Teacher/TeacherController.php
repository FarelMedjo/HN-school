<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Appreciation;
use App\Models\Convocation;
use App\Models\Cours;
use App\Models\Eleve;
use App\Models\Evaluation;
use App\Models\Frequente;
use App\Models\Presence;
use App\Models\Remarque;
use App\Models\Salle;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    private function personne()
    {
        return auth()->user()->personne;
    }

    private function myCours(): Collection
    {
        return $this->personne()?->coursEnseignes()->with('classe')->get() ?? collect();
    }

    private function elevesDuCours(Cours $cours): Collection
    {
        $annee = AnneeAcademique::where('actif', true)->first();
        $salleIds = Salle::where('idClasse', $cours->idClasse)->pluck('idSalle');
        $matricules = Frequente::whereIn('idSalle', $salleIds)
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->pluck('matricule');
        return Eleve::whereIn('matricule', $matricules)->orderBy('nom')->get();
    }

    private function abortIfNotMyCours(Cours $cours): void
    {
        $pers = $this->personne();
        abort_unless($pers && $cours->idPers === $pers->idPers, 403);
    }

    /** Élèves uniques de toutes les classes enseignées par cet enseignant. */
    private function mesEleves(): Collection
    {
        $annee     = AnneeAcademique::where('actif', true)->first();
        $classeIds = $this->myCours()->pluck('idClasse')->unique()->filter();
        $salleIds  = Salle::whereIn('idClasse', $classeIds)->pluck('idSalle');

        $matricules = Frequente::whereIn('idSalle', $salleIds)
            ->when($annee, fn ($q) => $q->where('idAcademi', $annee->idAnnee))
            ->pluck('matricule')->unique();

        return Eleve::whereIn('matricule', $matricules)->orderBy('nom')->orderBy('prenom')->get();
    }

    private function abortIfNotMyEleve($matricule): void
    {
        abort_unless($this->mesEleves()->contains('matricule', (int) $matricule), 403);
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $pers = $this->personne();
        $cours = $this->myCours();

        $annee = AnneeAcademique::where('actif', true)->first();
        $classeIds = $cours->pluck('idClasse')->unique()->filter();
        $salleIds  = Salle::whereIn('idClasse', $classeIds)->pluck('idSalle');

        $nbEleves = Frequente::whereIn('idSalle', $salleIds)
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->distinct('matricule')->count('matricule');

        $nbAbsences = Presence::whereIn('idSalle', $salleIds)
            ->where('statut', 'absent')
            ->where('date', '>=', now()->startOfWeek())
            ->count();

        $nbNotes = Evaluation::whereIn('idCours', $cours->pluck('idCours'))->count();

        return view('teacher.dashboard', compact('pers', 'cours', 'nbEleves', 'nbAbsences', 'nbNotes'));
    }

    // ── Élèves d'un cours ─────────────────────────────────────────────────────

    public function eleves(Cours $cours)
    {
        $this->abortIfNotMyCours($cours);
        $eleves = $this->elevesDuCours($cours);

        // Dernières notes par élève pour ce cours
        $notes = Evaluation::where('idCours', $cours->idCours)
            ->get()->keyBy('matricule');

        // Stats présences par élève
        $salle = Salle::where('idClasse', $cours->idClasse)->first();
        $presenceStats = Presence::selectRaw('matricule, statut, count(*) as total')
            ->where('idSalle', $salle?->idSalle)
            ->groupBy('matricule', 'statut')
            ->get()
            ->groupBy('matricule');

        return view('teacher.cours.eleves', compact('cours', 'eleves', 'notes', 'presenceStats'));
    }

    // ── Présences ─────────────────────────────────────────────────────────────

    public function presences(Request $request, Cours $cours)
    {
        $this->abortIfNotMyCours($cours);

        $date = $request->input('date', now()->toDateString());
        $salle = Salle::where('idClasse', $cours->idClasse)->first();
        $eleves = $this->elevesDuCours($cours);

        $presences = collect();
        if ($salle) {
            $presences = Presence::where('idSalle', $salle->idSalle)
                ->whereDate('date', $date)
                ->get()->keyBy('matricule');
        }

        return view('teacher.presences.index', compact('cours', 'salle', 'date', 'eleves', 'presences'));
    }

    public function storePresences(Request $request, Cours $cours)
    {
        $this->abortIfNotMyCours($cours);

        $data = $request->validate([
            'idSalle' => ['required', 'exists:salles,idSalle'],
            'date'    => ['required', 'date'],
            'statuts' => ['required', 'array'],
            'statuts.*' => ['required', 'in:present,absent,retard,justifie'],
            'motifs'  => ['nullable', 'array'],
        ]);

        $idPers = $this->personne()?->idPers;

        foreach ($data['statuts'] as $matricule => $statut) {
            Presence::updateOrCreate(
                ['matricule' => $matricule, 'idSalle' => $data['idSalle'], 'date' => $data['date']],
                ['statut' => $statut, 'motif' => $data['motifs'][$matricule] ?? null, 'idCours' => $cours->idCours, 'idPers' => $idPers]
            );
        }

        return redirect()
            ->route('teacher.cours.presences', ['cours' => $cours, 'date' => $data['date']])
            ->with('success', 'Pointage enregistré.');
    }

    // ── Notes ─────────────────────────────────────────────────────────────────

    public function notes(Cours $cours)
    {
        $this->abortIfNotMyCours($cours);
        $eleves = $this->elevesDuCours($cours);
        $evaluations = Evaluation::where('idCours', $cours->idCours)
            ->get()->keyBy('matricule');

        $section = $cours->classe?->section ?? 'francophone';

        return view('teacher.notes.index', compact('cours', 'eleves', 'evaluations', 'section'));
    }

    // ── Emploi du temps ───────────────────────────────────────────────────────

    public function emploiDuTemps()
    {
        $cours = $this->myCours();

        $classes = $cours->groupBy('idClasse')->map(function ($group) {
            $classe = $group->first()->classe;
            $edt = \App\Http\Controllers\Admin\EmploiDuTempsController::buildGrille($classe->idClasse);
            return [
                'libelle' => $classe->libelle,
                'grille'  => $edt['grille'],
                'palette' => $edt['palette'],
            ];
        })->values();

        return view('teacher.emploi-du-temps', compact('classes'));
    }

    // ── Vie scolaire (convocations, appréciations, journal) ─────────────────────

    public function vieScolaire()
    {
        $eleves     = $this->mesEleves();
        $matricules = $eleves->pluck('matricule');
        $trimestres = Trimestre::with('annee')->get();
        $idAuteur   = auth()->id();

        $convocations = Convocation::with('eleve')
            ->where('idAuteur', $idAuteur)
            ->whereIn('matricule', $matricules)
            ->orderByDesc('dateRdv')->take(30)->get();
        $appreciations = Appreciation::with(['eleve', 'trimestre'])
            ->where('idAuteur', $idAuteur)
            ->whereIn('matricule', $matricules)
            ->orderByDesc('updated_at')->take(30)->get();
        $remarques = Remarque::with('eleve')
            ->where('idAuteur', $idAuteur)
            ->whereIn('matricule', $matricules)
            ->orderByDesc('date')->take(30)->get();

        return view('teacher.vie-scolaire', compact('eleves', 'trimestres', 'convocations', 'appreciations', 'remarques'));
    }

    public function storeAppreciation(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'idTrimes'  => ['required', 'exists:trimestres,idTrimes'],
            'contenu'   => ['required', 'string'],
        ]);
        $this->abortIfNotMyEleve($data['matricule']);

        Appreciation::updateOrCreate(
            ['matricule' => $data['matricule'], 'idTrimes' => $data['idTrimes']],
            ['contenu' => $data['contenu'], 'idAuteur' => auth()->id()]
        );

        return redirect()->route('teacher.vie-scolaire')->with('success', 'Appréciation enregistrée.');
    }

    public function storeRemarque(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'date'      => ['required', 'date'],
            'categorie' => ['required', Rule::in(array_keys(Remarque::CATEGORIES))],
            'contenu'   => ['required', 'string'],
        ]);
        $this->abortIfNotMyEleve($data['matricule']);
        $data['idAuteur'] = auth()->id();

        Remarque::create($data);

        return redirect()->route('teacher.vie-scolaire')->with('success', 'Remarque enregistrée.');
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
        $this->abortIfNotMyEleve($data['matricule']);
        $data['idAuteur'] = auth()->id();

        $convocation = Convocation::create($data);

        return redirect()->route('teacher.convocations.show', $convocation)->with('success', 'Convocation enregistrée.');
    }

    public function convocationShow(Convocation $convocation)
    {
        $this->abortIfNotMyEleve($convocation->matricule);

        return app(\App\Http\Controllers\Admin\ConvocationController::class)->show($convocation);
    }

    public function storeNotes(Request $request, Cours $cours)
    {
        $this->abortIfNotMyCours($cours);

        $data = $request->validate([
            'notes'          => ['required', 'array'],
            'notes.*'        => ['nullable', 'numeric', 'min:0', 'max:' . ($cours->note ?? 20)],
            'appreciations'  => ['nullable', 'array'],
            'appreciations.*' => ['nullable', 'string', 'max:255'],
        ]);

        $idPers = $this->personne()?->idPers;

        foreach ($data['notes'] as $matricule => $note) {
            if ($note === null || $note === '') continue;
            Evaluation::updateOrCreate(
                ['matricule' => $matricule, 'idCours' => $cours->idCours],
                [
                    'note' => $note,
                    'appreciation' => $data['appreciations'][$matricule] ?? null,
                    'idPers' => $idPers,
                ]
            );
        }

        return redirect()
            ->route('teacher.cours.notes', $cours)
            ->with('success', 'Notes enregistrées.');
    }
}
