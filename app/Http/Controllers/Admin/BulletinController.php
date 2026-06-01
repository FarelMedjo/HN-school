<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Appreciation;
use App\Models\Cours;
use App\Models\Parametre;
use App\Models\Eleve;
use App\Models\Evaluation;
use App\Models\Frequente;
use App\Models\Presence;
use App\Models\Rapport;
use App\Models\SessionExamen;
use App\Models\Titulaire;
use App\Models\Trimestre;

class BulletinController extends Controller
{
    public function index()
    {
        $eleves     = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $trimestres = Trimestre::with('annee')->get();
        return view('admin.bulletins.index', compact('eleves', 'trimestres'));
    }

    public function show(Eleve $eleve, Trimestre $trimestre)
    {
        $annee = $trimestre->annee ?? AnneeAcademique::where('actif', true)->first();

        // Classe de l'élève pour cette année
        $frequente = Frequente::where('matricule', $eleve->matricule)
            ->where('idAcademi', $annee->idAnnee)
            ->with('salle.classe')
            ->first();

        $classe  = $frequente?->salle?->classe;
        $section = $classe?->section ?? 'francophone';

        // Cours de la classe
        $cours = $classe
            ? Cours::where('idClasse', $classe->idClasse)->orderBy('libelle')->get()
            : collect();

        // Notes filtrées par trimestre via idSession → session_examens.idTrimestre
        $sessionIds = SessionExamen::where('idTrimestre', $trimestre->idTrimes)->pluck('idSession');

        if ($sessionIds->isNotEmpty()) {
            $evaluations = Evaluation::where('matricule', $eleve->matricule)
                ->whereIn('idSession', $sessionIds)
                ->get()->keyBy('idCours');
        } else {
            // fallback : toutes les notes de l'élève pour ces cours
            $evaluations = Evaluation::where('matricule', $eleve->matricule)
                ->whereIn('idCours', $cours->pluck('idCours'))
                ->get()->keyBy('idCours');
        }

        // Absences & retards (toute l'année — indicateur général)
        $salleIds  = Frequente::where('matricule', $eleve->matricule)
            ->where('idAcademi', $annee->idAnnee)->pluck('idSalle');
        $absences  = Presence::where('matricule', $eleve->matricule)
            ->whereIn('idSalle', $salleIds)->where('statut', 'absent')->count();
        $retards   = Presence::where('matricule', $eleve->matricule)
            ->whereIn('idSalle', $salleIds)->where('statut', 'retard')->count();

        // Titulaire de la classe
        $titulaire = $classe
            ? Titulaire::where('idClasse', $classe->idClasse)->with('personne')->first()
            : null;

        // Rapport / comportement
        $rapport = Rapport::where('matricule', $eleve->matricule)
            ->where('idAca', $annee->idAnnee)->first();

        // Appréciation du trimestre (module Vie scolaire)
        $appreciation = Appreciation::where('matricule', $eleve->matricule)
            ->where('idTrimes', $trimestre->idTrimes)
            ->first();

        // Cachet / signature numérique (réglages établissement)
        $cachetUrl    = Parametre::fichierUrl('cachet');
        $signatureUrl = Parametre::fichierUrl('signature');
        $directeurNom = Parametre::get('directeur_nom');

        // Moyenne pondérée
        $notees  = $cours->filter(fn($c) => $evaluations->has($c->idCours));
        $coeffTotal = $notees->sum('coefficient');
        $moyenne = null;
        if ($notees->isNotEmpty() && $coeffTotal > 0) {
            $moyenne = $notees->sum(fn($c) => ($evaluations[$c->idCours]->note / $c->note) * $c->coefficient)
                / $coeffTotal * 20;
        }

        // Rang (position in class based on same trimester evaluations)
        $rang    = null;
        $effectif = null;
        if ($classe && $moyenne !== null) {
            $classMates = Frequente::where('idSalle', $frequente->idSalle)
                ->where('idAcademi', $annee->idAnnee)
                ->pluck('matricule');
            $effectif = $classMates->count();

            // Calcul des moyennes des camarades
            $moyennes = $classMates->map(function ($mat) use ($cours, $sessionIds, $evaluations) {
                $evals = $sessionIds->isNotEmpty()
                    ? Evaluation::where('matricule', $mat)->whereIn('idSession', $sessionIds)->get()->keyBy('idCours')
                    : Evaluation::where('matricule', $mat)->whereIn('idCours', $cours->pluck('idCours'))->get()->keyBy('idCours');
                $notees = $cours->filter(fn($c) => $evals->has($c->idCours));
                $coeff  = $notees->sum('coefficient');
                if ($coeff == 0) return 0;
                return $notees->sum(fn($c) => ($evals[$c->idCours]->note / $c->note) * $c->coefficient) / $coeff * 20;
            })->sort()->reverse()->values();

            $rang = $moyennes->search(fn($m) => abs($m - $moyenne) < 0.001) + 1;
        }

        return view('bulletins.show', compact(
            'eleve', 'trimestre', 'annee', 'classe', 'section',
            'cours', 'evaluations', 'absences', 'retards',
            'titulaire', 'rapport', 'appreciation', 'moyenne', 'rang', 'effectif',
            'cachetUrl', 'signatureUrl', 'directeurNom'
        ));
    }
}
