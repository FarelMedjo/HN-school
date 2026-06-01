<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Frequente;
use App\Models\ModePaiement;
use App\Models\Paiement;
use App\Models\Scolarite;
use Illuminate\Support\Collection;

class FinanceController extends Controller
{
    public function dashboard()
    {
        $annee = AnneeAcademique::where('actif', true)->first();

        $totalEncaisse = Paiement::when($annee, fn ($q) => $q->where('idAca', $annee->idAnnee))->sum('montant');
        $nbPaiements   = Paiement::when($annee, fn ($q) => $q->where('idAca', $annee->idAnnee))->count();

        $soldes      = $this->computeSoldes($annee);
        $totalDu     = $soldes->sum('du');
        $totalPaye   = $soldes->sum('paye');
        $resteAPayer = max(0, $totalDu - $totalPaye);
        $nbDebiteurs = $soldes->where('reste', '>', 0)->count();

        $derniersPaiements = Paiement::with('eleve', 'mode')
            ->when($annee, fn ($q) => $q->where('idAca', $annee->idAnnee))
            ->orderByDesc('datePaie')->take(8)->get();

        return view('finance.dashboard', compact(
            'annee', 'totalEncaisse', 'nbPaiements', 'resteAPayer', 'nbDebiteurs', 'derniersPaiements'
        ));
    }

    public function soldes()
    {
        $annee  = AnneeAcademique::where('actif', true)->first();
        $soldes = $this->computeSoldes($annee);
        return view('finance.soldes.index', compact('annee', 'soldes'));
    }

    public function rapports()
    {
        $annee  = AnneeAcademique::where('actif', true)->first();
        $soldes = $this->computeSoldes($annee);

        $totalDu          = $soldes->sum('du');
        $totalPaye        = $soldes->sum('paye');
        $resteAPayer      = max(0, $totalDu - $totalPaye);
        $tauxRecouvrement = $totalDu > 0 ? round($totalPaye / $totalDu * 100, 1) : 0;

        $parMode = Paiement::when($annee, fn ($q) => $q->where('idAca', $annee->idAnnee))
            ->selectRaw('idMode, count(*) as nb, sum(montant) as total')
            ->groupBy('idMode')->get()
            ->map(function ($row) {
                $row->libelle = $row->idMode
                    ? optional(ModePaiement::find($row->idMode))->libelle ?? 'Inconnu'
                    : 'Non précisé';
                return $row;
            });

        return view('finance.rapports.index', compact(
            'annee', 'totalDu', 'totalPaye', 'resteAPayer', 'tauxRecouvrement', 'parMode'
        ));
    }

    public function export()
    {
        $annee  = AnneeAcademique::where('actif', true)->first();
        $soldes = $this->computeSoldes($annee);

        $filename = 'soldes_'.($annee?->libelle ?? 'tous').'_'.now()->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($soldes) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 (Excel)
            fputcsv($out, ['Matricule', 'Nom', 'Prenom', 'Classe', 'Du (XAF)', 'Paye (XAF)', 'Reste (XAF)'], ';');
            foreach ($soldes as $s) {
                fputcsv($out, [
                    $s['matricule'], $s['nom'], $s['prenom'], $s['classe'],
                    $s['du'], $s['paye'], $s['reste'],
                ], ';');
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Solde par élève inscrit sur l'année active :
     * dû = inscription + pension du cycle de sa classe ; payé = somme des paiements de l'année.
     */
    private function computeSoldes(?AnneeAcademique $annee): Collection
    {
        $fraisParCycle = Scolarite::all()->groupBy('idCycle')->map(
            fn ($rows) => (float) $rows->first()->inscription + (float) $rows->first()->pension
        );

        $payeParEleve = Paiement::when($annee, fn ($q) => $q->where('idAca', $annee->idAnnee))
            ->selectRaw('matricule, sum(montant) as total')
            ->groupBy('matricule')->pluck('total', 'matricule');

        return Frequente::when($annee, fn ($q) => $q->where('idAcademi', $annee->idAnnee))
            ->with(['eleve', 'salle.classe'])
            ->get()
            ->map(function (Frequente $f) use ($fraisParCycle, $payeParEleve) {
                $eleve = $f->eleve;
                if (! $eleve) {
                    return null;
                }
                $classe = optional($f->salle)->classe;
                $du   = (float) ($fraisParCycle[optional($classe)->idCycle] ?? 0);
                $paye = (float) ($payeParEleve[$eleve->matricule] ?? 0);

                return [
                    'matricule' => $eleve->matricule,
                    'nom'       => $eleve->nom,
                    'prenom'    => $eleve->prenom,
                    'classe'    => optional($classe)->libelle ?? '—',
                    'du'        => $du,
                    'paye'      => $paye,
                    'reste'     => max(0, $du - $paye),
                ];
            })
            ->filter()
            ->unique('matricule')
            ->values();
    }
}
