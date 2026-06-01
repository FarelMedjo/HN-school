<?php

namespace Database\Seeders;

use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\Cycle;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Evaluation;
use App\Models\Frequente;
use App\Models\EmploiDuTemps;
use App\Models\Message;
use App\Models\ModePaiement;
use App\Models\Paiement;
use App\Models\ParentEleve;
use App\Models\Personne;
use App\Models\Salle;
use App\Models\Scolarite;
use App\Models\SessionExamen;
use App\Models\Tranche;
use App\Models\Trimestre;
use App\Models\User;
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

        // Lier le compte auth enseignant à sa fiche Personne
        User::where('email', 'enseignant@hnschool.test')
            ->update(['idPers' => $persEns->idPers]);

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

        // Lier le compte auth parent à sa fiche Personne
        User::where('email', 'parent@hnschool.test')
            ->update(['idPers' => $persParent->idPers]);

        // Messages démo admin → parent
        Message::firstOrCreate(
            ['idParent' => $persParent->idPers, 'objet' => 'Bienvenue à HN-School'],
            [
                'idExp_Pers'   => null,
                'information'  => "Bonjour Mme Kouam,\n\nNous sommes ravis de vous accueillir dans l'espace parent de HN-School.\nVous pouvez consulter ici les notes, les absences et les paiements de vos enfants.\n\nCordialement,\nL'Administration",
                'type_message' => 1,
                'AnneeAcade'   => $annee->libelle,
                'valider'      => false,
            ]
        );
        Message::firstOrCreate(
            ['idParent' => $persParent->idPers, 'objet' => 'Réunion de parents — Trimestre 1'],
            [
                'idExp_Pers'   => null,
                'information'  => "Bonjour,\n\nNous vous convions à la réunion de parents du Trimestre 1 qui aura lieu le samedi 15 novembre 2025 à 9h00 dans la salle polyvalente.\n\nVotre présence est vivement souhaitée.\n\nCordialement,\nLa Direction",
                'type_message' => 3,
                'AnneeAcade'   => $annee->libelle,
                'valider'      => false,
            ]
        );

        // Lier le compte auth élève à Kouam Léa
        User::where('email', 'eleve@hnschool.test')
            ->update(['matricule' => $eleves[0]->matricule]);

        // Emploi du temps démo pour CP1
        $francais = Cours::where('libelle', 'Français')->where('idClasse', $cp->idClasse)->first();
        $edtSlots = [
            ['Lundi',    '07:30', $math],
            ['Lundi',    '08:30', $francais],
            ['Lundi',    '09:30', $math],
            ['Mardi',    '07:30', $francais],
            ['Mardi',    '08:30', $math],
            ['Mardi',    '09:30', $francais],
            ['Mercredi', '07:30', $math],
            ['Mercredi', '08:30', $francais],
            ['Jeudi',    '07:30', $francais],
            ['Jeudi',    '08:30', $math],
            ['Jeudi',    '09:30', $francais],
            ['Vendredi', '07:30', $math],
            ['Vendredi', '08:30', $francais],
            ['Vendredi', '09:30', $math],
        ];
        foreach ($edtSlots as [$jour, $heure, $coursEdt]) {
            if (!$coursEdt) continue;
            EmploiDuTemps::firstOrCreate(
                ['idClasse' => $cp->idClasse, 'jour' => $jour, 'heure' => $heure],
                ['idCours' => $coursEdt->idCours, 'idAdmin' => 1]
            );
        }

        // Session d'examen liée au Trimestre 1
        $t1 = Trimestre::where('libelle', 'Trimestre 1')->where('idAcad', $annee->idAnnee)->first();
        if ($t1) {
            $session = SessionExamen::firstOrCreate(
                ['libelle' => 'Examen T1', 'idTrimestre' => $t1->idTrimes],
                ['description' => 'Évaluations du premier trimestre', 'idPers' => $persEns->idPers]
            );

            // Notes démo pour les 4 élèves CP1
            $notesDemos = [
                [$eleves[0], $math, 16.5, 'Très bon travail'],
                [$eleves[0], Cours::where('libelle', 'Français')->first(), 14.0, 'Bon niveau'],
                [$eleves[1], $math, 12.0, 'Peut mieux faire'],
                [$eleves[1], Cours::where('libelle', 'Français')->first(), 15.5, 'Excellent'],
                [$eleves[2], $math, 18.0, 'Félicitations'],
                [$eleves[2], Cours::where('libelle', 'Français')->first(), 17.0, 'Très bien'],
                [$eleves[3], $math, 9.5, 'Des efforts à fournir'],
                [$eleves[3], Cours::where('libelle', 'Français')->first(), 11.0, 'Encourage'],
            ];

            foreach ($notesDemos as [$eleve, $cours, $note, $appr]) {
                if (!$cours) continue;
                Evaluation::firstOrCreate(
                    ['matricule' => $eleve->matricule, 'idCours' => $cours->idCours, 'idSession' => $session->idSession],
                    ['note' => $note, 'appreciation' => $appr, 'idPers' => $persEns->idPers]
                );
            }
        }
    }
}
