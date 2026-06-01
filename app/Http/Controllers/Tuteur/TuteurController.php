<?php

namespace App\Http\Controllers\Tuteur;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Appreciation;
use App\Models\Convocation;
use App\Models\Cours;
use App\Models\Eleve;
use App\Models\Emprunt;
use App\Models\Evaluation;
use App\Models\Frequente;
use App\Models\Message;
use App\Models\Paiement;
use App\Models\ParentEleve;
use App\Models\Presence;
use App\Models\Remarque;
use App\Models\Salle;
use App\Models\Trimestre;
use Illuminate\Support\Collection;

class TuteurController extends Controller
{
    private function personne()
    {
        return auth()->user()->personne;
    }

    private function mesEnfants(): Collection
    {
        $pers = $this->personne();
        if (!$pers) return collect();

        return ParentEleve::where('idPers', $pers->idPers)
            ->with(['eleve.frequentations' => function ($q) {
                $annee = AnneeAcademique::where('actif', true)->first();
                if ($annee) $q->where('idAcademi', $annee->idAnnee);
            }, 'eleve.frequentations.salle.classe'])
            ->get()
            ->pluck('eleve')
            ->filter();
    }

    private function abortIfNotMyChild(Eleve $eleve): void
    {
        $pers = $this->personne();
        abort_unless(
            $pers && ParentEleve::where('idPers', $pers->idPers)
                ->where('matricule', $eleve->matricule)->exists(),
            403
        );
    }

    private function classeActuelle(Eleve $eleve): ?string
    {
        $annee = AnneeAcademique::where('actif', true)->first();
        $f = $eleve->frequentations()
            ->with('salle.classe')
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->first();
        return optional(optional($f?->salle)->classe)->libelle;
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $pers = $this->personne();
        $enfants = $this->mesEnfants();

        $annee = AnneeAcademique::where('actif', true)->first();

        // Pour chaque enfant : absences cette semaine + total payé
        $stats = $enfants->mapWithKeys(function (Eleve $e) use ($annee) {
            $anneeActive = AnneeAcademique::where('actif', true)->first();
            $salleIds = Frequente::where('matricule', $e->matricule)
                ->when($anneeActive, fn($q) => $q->where('idAcademi', $anneeActive->idAnnee))
                ->pluck('idSalle');

            $absences = Presence::whereIn('idSalle', $salleIds)
                ->where('matricule', $e->matricule)
                ->where('statut', 'absent')
                ->where('date', '>=', now()->startOfWeek())
                ->count();

            $totalPaye = Paiement::where('matricule', $e->matricule)
                ->when($anneeActive, fn($q) => $q->where('idAca', $anneeActive->idAnnee))
                ->sum('montant');

            return [$e->matricule => compact('absences', 'totalPaye')];
        });

        return view('parent.dashboard', compact('pers', 'enfants', 'stats'));
    }

    // ── Fiche enfant ─────────────────────────────────────────────────────────

    public function enfant(Eleve $eleve)
    {
        $this->abortIfNotMyChild($eleve);

        $annee = AnneeAcademique::where('actif', true)->first();
        $salleIds = Frequente::where('matricule', $eleve->matricule)
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->pluck('idSalle');

        $classe = $this->classeActuelle($eleve);

        $nbAbsences  = Presence::where('matricule', $eleve->matricule)
            ->whereIn('idSalle', $salleIds)->where('statut', 'absent')->count();
        $nbRetards   = Presence::where('matricule', $eleve->matricule)
            ->whereIn('idSalle', $salleIds)->where('statut', 'retard')->count();
        $totalPaye   = Paiement::where('matricule', $eleve->matricule)
            ->when($annee, fn($q) => $q->where('idAca', $annee->idAnnee))->sum('montant');
        $nbEvals     = Evaluation::where('matricule', $eleve->matricule)->count();

        return view('parent.enfant.show', compact(
            'eleve', 'classe', 'nbAbsences', 'nbRetards', 'totalPaye', 'nbEvals'
        ));
    }

    // ── Notes ─────────────────────────────────────────────────────────────────

    public function notes(Eleve $eleve)
    {
        $this->abortIfNotMyChild($eleve);

        $annee = AnneeAcademique::where('actif', true)->first();
        $classe = $this->classeActuelle($eleve);

        // Cours de la classe de l'élève
        $frequente = Frequente::where('matricule', $eleve->matricule)
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->with('salle.classe')
            ->first();

        $idClasse = $frequente?->salle?->classe?->idClasse;
        $cours = $idClasse
            ? Cours::where('idClasse', $idClasse)->orderBy('libelle')->get()
            : collect();

        // Notes de l'élève indexées par cours
        $evaluations = Evaluation::where('matricule', $eleve->matricule)
            ->get()->keyBy('idCours');

        $section = $frequente?->salle?->classe?->section ?? 'francophone';

        return view('parent.enfant.notes', compact('eleve', 'classe', 'cours', 'evaluations', 'section'));
    }

    // ── Absences ─────────────────────────────────────────────────────────────

    public function presences(Eleve $eleve)
    {
        $this->abortIfNotMyChild($eleve);

        $annee = AnneeAcademique::where('actif', true)->first();
        $classe = $this->classeActuelle($eleve);

        $salleIds = Frequente::where('matricule', $eleve->matricule)
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->pluck('idSalle');

        $presences = Presence::where('matricule', $eleve->matricule)
            ->whereIn('idSalle', $salleIds)
            ->orderByDesc('date')
            ->paginate(20);

        $counts = Presence::where('matricule', $eleve->matricule)
            ->whereIn('idSalle', $salleIds)
            ->selectRaw('statut, count(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        return view('parent.enfant.presences', compact('eleve', 'classe', 'presences', 'counts'));
    }

    // ── Paiements ─────────────────────────────────────────────────────────────

    // ── Messagerie ────────────────────────────────────────────────────────────

    public function messages()
    {
        $idPers = $this->personne()?->idPers;
        abort_unless($idPers, 403);

        $messages = Message::where('idParent', $idPers)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('parent.messages.index', compact('messages'));
    }

    public function showMessage(Message $message)
    {
        $idPers = $this->personne()?->idPers;
        abort_unless($idPers && $message->idParent == $idPers, 403);

        // Marquer comme lu
        if (!$message->valider) {
            $message->update(['valider' => true]);
        }

        $message->load('expediteur');
        return view('parent.messages.show', compact('message'));
    }

    // ── Emploi du temps ───────────────────────────────────────────────────────

    public function emploiDuTemps(Eleve $eleve)
    {
        $this->abortIfNotMyChild($eleve);

        $annee = AnneeAcademique::where('actif', true)->first();
        $classe = $this->classeActuelle($eleve);

        $frequente = Frequente::where('matricule', $eleve->matricule)
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->with('salle.classe')
            ->first();

        $idClasse = $frequente?->salle?->classe?->idClasse;
        $grille  = collect();
        $palette = collect();
        if ($idClasse) {
            $edt     = \App\Http\Controllers\Admin\EmploiDuTempsController::buildGrille($idClasse);
            $grille  = $edt['grille'];
            $palette = $edt['palette'];
        }

        return view('parent.enfant.emploi-du-temps', compact('eleve', 'classe', 'grille', 'palette'));
    }

    public function bulletin(Eleve $eleve, Trimestre $trimestre)
    {
        $this->abortIfNotMyChild($eleve);
        // Délègue au BulletinController (réutilise la même vue)
        return app(\App\Http\Controllers\Admin\BulletinController::class)->show($eleve, $trimestre);
    }

    public function paiements(Eleve $eleve)
    {
        $this->abortIfNotMyChild($eleve);

        $annee = AnneeAcademique::where('actif', true)->first();
        $classe = $this->classeActuelle($eleve);

        $paiements = Paiement::where('matricule', $eleve->matricule)
            ->when($annee, fn($q) => $q->where('idAca', $annee->idAnnee))
            ->with('mode')
            ->orderByDesc('datePaie')
            ->get();

        $totalPaye = $paiements->sum('montant');

        return view('parent.enfant.paiements', compact('eleve', 'classe', 'paiements', 'totalPaye', 'annee'));
    }

    // ── Vie scolaire (convocations, appréciations, journal) ─────────────────────

    public function vieScolaire(Eleve $eleve)
    {
        $this->abortIfNotMyChild($eleve);

        $classe = $this->classeActuelle($eleve);

        $convocations = Convocation::where('matricule', $eleve->matricule)
            ->orderByDesc('dateRdv')->get();
        $appreciations = Appreciation::with('trimestre')
            ->where('matricule', $eleve->matricule)
            ->orderByDesc('idTrimes')->get();
        $remarques = Remarque::where('matricule', $eleve->matricule)
            ->orderByDesc('date')->orderByDesc('idRemarque')->get();

        return view('parent.enfant.vie-scolaire', compact('eleve', 'classe', 'convocations', 'appreciations', 'remarques'));
    }

    public function convocationShow(Convocation $convocation)
    {
        $eleve = Eleve::where('matricule', $convocation->matricule)->firstOrFail();
        $this->abortIfNotMyChild($eleve);

        return app(\App\Http\Controllers\Admin\ConvocationController::class)->show($convocation);
    }

    // ── Emprunts (bibliothèque) ─────────────────────────────────────────────────

    public function emprunts(Eleve $eleve)
    {
        $this->abortIfNotMyChild($eleve);

        $classe = $this->classeActuelle($eleve);

        $emprunts = Emprunt::with('livre')
            ->where('matricule', $eleve->matricule)
            ->orderByDesc('idEmprunt')
            ->paginate(20);

        return view('parent.enfant.emprunts', compact('eleve', 'classe', 'emprunts'));
    }
}
