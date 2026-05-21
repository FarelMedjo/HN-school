<?php

namespace Database\Seeders;

use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\Cycle;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Frequente;
use App\Models\ModePaiement;
use App\Models\Paiement;
use App\Models\ParentEleve;
use App\Models\Personne;
use App\Models\Salle;
use App\Models\Scolarite;
use App\Models\Tranche;
use App\Models\Trimestre;
use App\Models\VilleNaissance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Cycles
        $creche = Cycle::firstOrCreate(['libelle' => 'Crèche'], ['description' => 'Garderie crèche']);
        $maternel = Cycle::firstOrCreate(['libelle' => 'Maternel'], ['description' => 'Petite, moyenne et grande section']);
        $primaire = Cycle::firstOrCreate(['libelle' => 'Primaire'], ['description' => 'Cycle primaire (anglophone, francophone, bilingue)']);

        // Classes
        $cp = Classe::firstOrCreate(['libelle' => 'CP1 Francophone'], ['idCycle' => $primaire->idCycle]);
        $ce1 = Classe::firstOrCreate(['libelle' => 'CE1 Bilingue'], ['idCycle' => $primaire->idCycle]);
        $msm = Classe::firstOrCreate(['libelle' => 'Moyenne Section'], ['idCycle' => $maternel->idCycle]);

        // Salles
        Salle::firstOrCreate(['libelle' => 'Salle A1'], ['position' => 'Bloc A', 'surface' => '40m2', 'idClasse' => $cp->idClasse]);
        Salle::firstOrCreate(['libelle' => 'Salle A2'], ['position' => 'Bloc A', 'surface' => '40m2', 'idClasse' => $ce1->idClasse]);
        Salle::firstOrCreate(['libelle' => 'Salle B1'], ['position' => 'Bloc B', 'surface' => '35m2', 'idClasse' => $msm->idClasse]);

        // Année académique
        $annee = AnneeAcademique::firstOrCreate(
            ['libelle' => '2025-2026'],
            ['periode' => 'Septembre 2025 - Juillet 2026', 'actif' => true]
        );

        // Trimestres
        foreach (['Trimestre 1', 'Trimestre 2', 'Trimestre 3'] as $i => $lib) {
            Trimestre::firstOrCreate(
                ['libelle' => $lib, 'idAcad' => $annee->idAnnee],
                ['periode' => 'T'.($i + 1)]
            );
        }

        // Scolarité + tranches
        $sco = Scolarite::firstOrCreate(
            ['idCycle' => $primaire->idCycle, 'description' => 'Primaire 2025-2026'],
            ['inscription' => 50000, 'pension' => 300000, 'nbreTranche' => 3]
        );
        Tranche::firstOrCreate(['libelle' => '1ère tranche', 'idScolarite' => $sco->idScolarite], ['montant' => 100000, 'delai_mois' => '10', 'delai_jour' => '15']);
        Tranche::firstOrCreate(['libelle' => '2ème tranche', 'idScolarite' => $sco->idScolarite], ['montant' => 100000, 'delai_mois' => '01', 'delai_jour' => '15']);
        Tranche::firstOrCreate(['libelle' => '3ème tranche', 'idScolarite' => $sco->idScolarite], ['montant' => 100000, 'delai_mois' => '04', 'delai_jour' => '15']);

        // Modes de paiement
        ModePaiement::firstOrCreate(['libelle' => 'Espèces']);
        $orange = ModePaiement::firstOrCreate(['libelle' => 'Orange Money']);
        ModePaiement::firstOrCreate(['libelle' => 'Virement bancaire']);

        // Ville
        $douala = VilleNaissance::firstOrCreate(['libelle' => 'Douala']);
        VilleNaissance::firstOrCreate(['libelle' => 'Yaoundé']);

        // Personne enseignant
        $persEns = Personne::firstOrCreate(
            ['username' => 'mballa.ens'],
            [
                'nom' => 'Mballa',
                'prenom' => 'Jean',
                'dateNaissance' => '1985-04-12',
                'lieuNaissance' => 'Douala',
                'mobile' => '690000001',
                'phone' => '233000001',
                'typePersonne' => 2, // 2 = enseignant
                'password' => Hash::make('password'),
            ]
        );

        // Cours
        $math = Cours::firstOrCreate(
            ['libelle' => 'Mathématiques', 'idClasse' => $cp->idClasse],
            ['note' => 20, 'coefficient' => 4, 'idPers' => $persEns->idPers]
        );
        Cours::firstOrCreate(
            ['libelle' => 'Français', 'idClasse' => $cp->idClasse],
            ['note' => 20, 'coefficient' => 4, 'idPers' => $persEns->idPers]
        );

        // Enseignant
        Enseignant::firstOrCreate(
            ['idPers' => $persEns->idPers, 'idCours' => $math->idCours],
            ['Actif' => true]
        );

        // Personne parent
        $persParent = Personne::firstOrCreate(
            ['username' => 'kouam.parent'],
            [
                'nom' => 'Kouam',
                'prenom' => 'Marie',
                'dateNaissance' => '1980-08-20',
                'lieuNaissance' => 'Yaoundé',
                'mobile' => '699000001',
                'typePersonne' => 3, // 3 = parent
                'password' => Hash::make('password'),
            ]
        );

        // Élèves démo
        $prenoms = [
            ['Kouam', 'Léa', '2018-03-10'],
            ['Kouam', 'Noé', '2017-09-05'],
            ['Tchoua', 'Aïcha', '2018-12-22'],
            ['Mbarga', 'Yvan', '2017-06-15'],
            ['Onana', 'Sara', '2018-11-30'],
        ];
        $eleves = [];
        foreach ($prenoms as $i => [$nom, $prenom, $dn]) {
            $eleves[] = Eleve::firstOrCreate(
                ['nom' => $nom, 'prenom' => $prenom],
                [
                    'dateNaissance' => $dn,
                    'lieuNaissance' => 'Douala',
                    'sexe' => $i % 2,
                    'langue' => 'Français',
                    'idVilleNaissance' => $douala->idVille,
                    'actif' => true,
                ]
            );
        }

        // Affectation parent → 2 premiers enfants
        ParentEleve::firstOrCreate(['idPers' => $persParent->idPers, 'matricule' => $eleves[0]->matricule]);
        ParentEleve::firstOrCreate(['idPers' => $persParent->idPers, 'matricule' => $eleves[1]->matricule]);

        // Affectation classe (frequente) — tous en CP1 sauf le dernier en MS
        $salleCp = Salle::where('idClasse', $cp->idClasse)->first();
        $salleMs = Salle::where('idClasse', $msm->idClasse)->first();
        foreach ($eleves as $i => $e) {
            $salle = $i < 4 ? $salleCp : $salleMs;
            Frequente::firstOrCreate(
                ['matricule' => $e->matricule, 'idAcademi' => $annee->idAnnee],
                ['idSalle' => $salle?->idSalle, 'commentaire' => 'Affectation initiale']
            );
        }

        // Paiements démo
        Paiement::firstOrCreate(
            ['matricule' => $eleves[0]->matricule, 'idAca' => $annee->idAnnee, 'commentaire' => 'Inscription + 1ère tranche'],
            ['montant' => 150000, 'idMode' => $orange->idMode, 'datePaie' => now()->subDays(20), 'operation_ID' => 'OM-DEMO-001']
        );
        Paiement::firstOrCreate(
            ['matricule' => $eleves[1]->matricule, 'idAca' => $annee->idAnnee, 'commentaire' => 'Inscription'],
            ['montant' => 50000, 'idMode' => $orange->idMode, 'datePaie' => now()->subDays(15), 'operation_ID' => 'OM-DEMO-002']
        );
    }
}
