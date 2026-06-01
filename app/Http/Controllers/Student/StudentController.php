<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Admin\EmploiDuTempsController;
use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Appreciation;
use App\Models\Convocation;
use App\Models\Cours;
use App\Models\Eleve;
use App\Models\Emprunt;
use App\Models\Evaluation;
use App\Models\Frequente;
use App\Models\Presence;
use App\Models\Remarque;
use App\Models\Trimestre;

class StudentController extends Controller
{
    /** Élève connecté */
    private function eleve(): ?Eleve
    {
        return auth()->user()->eleve;
    }

    /** Classe actuelle de l'élève (libelle) */
    private function classeActuelle(Eleve $eleve): ?string
    {
        $annee = AnneeAcademique::where('actif', true)->first();
        $f = $eleve->frequentations()
            ->with('salle.classe')
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->first();
        return optional(optional($f?->salle)->classe)->libelle;
    }

    /** idClasse de l'élève courant (année active) */
    private function idClasse(Eleve $eleve): ?int
    {
        $annee = AnneeAcademique::where('actif', true)->first();
        $f = $eleve->frequentations()
            ->with('salle.classe')
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->first();
        return $f?->salle?->classe?->idClasse;
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $eleve = $this->eleve();
        abort_unless($eleve, 403, 'Aucun dossier élève associé à ce compte.');

        $annee  = AnneeAcademique::where('actif', true)->first();
        $classe = $this->classeActuelle($eleve);

        $salleIds = Frequente::where('matricule', $eleve->matricule)
            ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
            ->pluck('idSalle');

        $nbNotes    = Evaluation::where('matricule', $eleve->matricule)->count();
        $nbAbsences = Presence::where('matricule', $eleve->matricule)
            ->whereIn('idSalle', $salleIds)
            ->where('statut', 'absent')->count();
        $nbRetards  = Presence::where('matricule', $eleve->matricule)
            ->whereIn('idSalle', $salleIds)
            ->where('statut', 'retard')->count();

        return view('student.dashboard', compact('eleve', 'classe', 'nbNotes', 'nbAbsences', 'nbRetards', 'annee'));
    }

    // ── Notes ─────────────────────────────────────────────────────────────────

    public function notes()
    {
        $eleve = $this->eleve();
        abort_unless($eleve, 403);

        $annee  = AnneeAcademique::where('actif', true)->first();
        $classe = $this->classeActuelle($eleve);

        $idClasse = $this->idClasse($eleve);
        $cours = $idClasse
            ? Cours::where('idClasse', $idClasse)->orderBy('libelle')->get()
            : collect();

        $evaluations = Evaluation::where('matricule', $eleve->matricule)
            ->get()->keyBy('idCours');

        $section = 'francophone'; // fallback; pourrait venir de la classe
        if ($idClasse) {
            $f = $eleve->frequentations()->with('salle.classe')
                ->when($annee, fn($q) => $q->where('idAcademi', $annee->idAnnee))
                ->first();
            $section = $f?->salle?->classe?->section ?? 'francophone';
        }

        // Calcul de la moyenne générale
        $total = $coeff = 0;
        foreach ($cours as $c) {
            $eval = $evaluations->get($c->idCours);
            if ($eval && $eval->note !== null && $c->note > 0) {
                $sur20   = ($eval->note / $c->note) * 20;
                $total  += $sur20 * $c->coefficient;
                $coeff  += $c->coefficient;
            }
        }
        $moyenne = $coeff > 0 ? round($total / $coeff, 2) : null;

        return view('student.notes', compact('eleve', 'classe', 'cours', 'evaluations', 'section', 'moyenne'));
    }

    // ── Absences ─────────────────────────────────────────────────────────────

    public function absences()
    {
        $eleve = $this->eleve();
        abort_unless($eleve, 403);

        $annee  = AnneeAcademique::where('actif', true)->first();
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

        return view('student.absences', compact('eleve', 'classe', 'presences', 'counts'));
    }

    // ── Emploi du temps ───────────────────────────────────────────────────────

    public function emploiDuTemps()
    {
        $eleve = $this->eleve();
        abort_unless($eleve, 403);

        $annee   = AnneeAcademique::where('actif', true)->first();
        $classe  = $this->classeActuelle($eleve);
        $idClasse = $this->idClasse($eleve);

        $grille  = collect();
        $palette = collect();
        if ($idClasse) {
            $edt     = EmploiDuTempsController::buildGrille($idClasse);
            $grille  = $edt['grille'];
            $palette = $edt['palette'];
        }

        return view('student.emploi-du-temps', compact('eleve', 'classe', 'grille', 'palette'));
    }

    // ── Bulletin ──────────────────────────────────────────────────────────────

    public function bulletin()
    {
        $eleve = $this->eleve();
        abort_unless($eleve, 403);

        $annee     = AnneeAcademique::where('actif', true)->first();
        $trimestres = $annee
            ? Trimestre::where('idAcad', $annee->idAnnee)->orderBy('idTrimes')->get()
            : collect();

        $classe = $this->classeActuelle($eleve);

        return view('student.bulletin', compact('eleve', 'classe', 'trimestres'));
    }

    public function bulletinShow(Trimestre $trimestre)
    {
        $eleve = $this->eleve();
        abort_unless($eleve, 403);

        return app(\App\Http\Controllers\Admin\BulletinController::class)->show($eleve, $trimestre);
    }

    // ── Vie scolaire (convocations, appréciations, journal) ─────────────────────

    public function vieScolaire()
    {
        $eleve = $this->eleve();
        abort_unless($eleve, 403);

        $classe = $this->classeActuelle($eleve);

        $convocations = Convocation::where('matricule', $eleve->matricule)
            ->orderByDesc('dateRdv')->get();
        $appreciations = Appreciation::with('trimestre')
            ->where('matricule', $eleve->matricule)
            ->orderByDesc('idTrimes')->get();
        $remarques = Remarque::where('matricule', $eleve->matricule)
            ->orderByDesc('date')->orderByDesc('idRemarque')->get();

        return view('student.vie-scolaire', compact('eleve', 'classe', 'convocations', 'appreciations', 'remarques'));
    }

    public function convocationShow(Convocation $convocation)
    {
        $eleve = $this->eleve();
        abort_unless($eleve && $convocation->matricule == $eleve->matricule, 403);

        return app(\App\Http\Controllers\Admin\ConvocationController::class)->show($convocation);
    }

    // ── Emprunts (bibliothèque) ─────────────────────────────────────────────────

    public function emprunts()
    {
        $eleve = $this->eleve();
        abort_unless($eleve, 403);

        $classe = $this->classeActuelle($eleve);

        $emprunts = Emprunt::with('livre')
            ->where('matricule', $eleve->matricule)
            ->orderByDesc('idEmprunt')
            ->paginate(20);

        return view('student.emprunts', compact('eleve', 'classe', 'emprunts'));
    }
}
