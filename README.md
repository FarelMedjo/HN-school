# HN-School

Application web Laravel de gestion d'école : crèche, maternelle, primaire (anglophone, francophone, bilingue), avec gestion des élèves, parents, enseignants, classes, paiements, scolarités, évaluations, présences, bulletins, emploi du temps et messagerie.

## Stack

- **Laravel 13** (PHP 8.3)
- **MySQL** (Laragon)
- **Laravel Breeze** (auth Blade)
- **Spatie Laravel Permission** (rôles & permissions)
- **Tailwind CSS + Alpine.js + Vite**
- **Bulletins** via `window.print()` du navigateur (pas de DomPDF)

## Rôles & espaces

Six rôles, chacun avec son espace isolé (préfixe d'URL, layout et couleur dédiés). La route `/dashboard` redirige automatiquement vers le bon espace selon le rôle Spatie.

| Rôle         | Préfixe      | Layout              | Périmètre |
|--------------|--------------|---------------------|-----------|
| `admin`      | `/admin`     | `x-admin-layout`    | Administration complète |
| `scolarite`  | `/scolarite` | `x-scolarite-layout`| Élèves, classes, années, présences, évaluations, bulletins |
| `finance`    | `/finance`   | `x-finance-layout`  | Paiements, scolarités, modes, soldes, rapports |
| `enseignant` | `/teacher`   | `x-teacher-layout`  | Cours, notes, présences, emploi du temps |
| `parent`     | `/parent`    | `x-parent-layout`   | Suivi enfant(s), notes, absences, paiements, bulletins, messagerie |
| `eleve`      | `/student`   | `x-student-layout`  | Notes, absences, emploi du temps, bulletin |

> **`parent` est un mot réservé PHP** — le contrôleur du rôle parent vit dans `App\Http\Controllers\Tuteur\TuteurController`.

## Comptes de démonstration

| Rôle        | Email                       | Mot de passe | Lié à |
|-------------|-----------------------------|--------------|-------|
| Admin       | admin@hnschool.test         | password     | — |
| Scolarité   | scolarite@hnschool.test     | password     | — |
| Finance     | finance@hnschool.test       | password     | — |
| Enseignant  | enseignant@hnschool.test    | password     | Personne : Mballa Jean |
| Parent      | parent@hnschool.test        | password     | Personne : Kouam Marie (parent de Léa & Noé) |
| Élève       | eleve@hnschool.test         | password     | Élève : Kouam Léa (CP1 Francophone) |

## Installation

> **Windows / Laragon** : les commandes `php` / `artisan` doivent utiliser le chemin complet de Laragon, p. ex.
> `C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan <commande>`. Utiliser **PowerShell**.

```powershell
# 1. Dépendances PHP
composer install

# 2. Dépendances JS
npm install

# 3. Environnement
cp .env.example .env
php artisan key:generate

# Configurer .env :
# DB_CONNECTION=mysql
# DB_DATABASE=hn_school
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Créer la base
mysql -uroot -e "CREATE DATABASE hn_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Migrer + seeder (reset complet avec données de démo)
php artisan migrate:fresh --seed

# 6. Build assets
npm run build

# 7. Démarrer (serveur + queue + logs + Vite en une commande)
composer run dev
```

Ouvrir http://127.0.0.1:8000 et se connecter avec un compte démo.

## Modèle de données

38 tables métier fidèles au MCD, regroupées en lots :

- **Référentiels** : `cycles`, `classes` (+ colonne `section`), `salles`, `jour_semaines`, `tranches`, `horaires`, `scolarites`, `mode_paiements`, `nature_epreuves`, `disciplines`, `quartiers`, `ville_naissances`, `specialites`, `parametres` (établissement)
- **Académique** : `annee_academiques`, `trimestres`, `session_examens`, `cours`, `emploi_du_temps`, `epreuves`
- **Personnes** : `personnes`, `admins`, `enseignants`, `eleves`, `parent_eleves`, `residents`, `titulaires`
- **Activités** : `frequentes`, `evaluations`, `presences`, `rapports`, `paiements`, `messages`, `livres`, `emprunts`, `appreciations`, `remarques`, `convocations`

La table `users` (Breeze) est reliée au domaine par trois colonnes ajoutées : `idPers` → `personnes`, `matricule` → `eleves`, et `actif` (activation/désactivation des comptes).

## Fonctionnalités par espace

### Administration (`/admin`)
CRUD : années académiques, cycles, classes, salles, élèves (avec affectation classe), enseignants, parents, cours/matières, paiements, scolarités, évaluations. Plus : **bulletins**, **messagerie**, **présences** (saisie + consultation par élève), **emploi du temps** interactif (+ configuration des **horaires**), **bibliothèque** (catalogue `livres` + gestion des `emprunts`/retours), **vie scolaire** (appréciations, remarques, convocations), **paramètres de l'établissement** (identité, en-tête des bulletins), **gestion des utilisateurs** (changement de rôle + activation/désactivation).

### Scolarité (`/scolarite`)
Élèves & affectations, années, cycles, classes, salles, évaluations, présences, emploi du temps et bulletins.

### Finance (`/finance`)
Paiements, scolarités, modes de paiement, soldes par élève, rapports et export.

### Enseignant (`/teacher`)
Liste des cours et des élèves par cours, saisie des présences, saisie des notes, emploi du temps, et **vie scolaire** (saisie d'appréciations, remarques et convocations).

### Parent (`/parent`)
Suivi de chaque enfant : notes, absences, paiements, bulletins par trimestre, emploi du temps, vie scolaire (convocations reçues), emprunts à la bibliothèque, et messagerie (lecture des messages reçus).

### Élève (`/student`)
Notes, absences, emploi du temps, bulletin par trimestre, vie scolaire (convocations) et emprunts à la bibliothèque.

## Points d'architecture clés

> Détails complets dans [CLAUDE.md](CLAUDE.md).

- **Chaîne d'identité** : `User → Personne` (via `idPers`, enseignants/parents) ou `User → Eleve` (via `matricule`, élèves).
- **Classe d'un élève** : pas de `eleve.idClasse` direct — résolue via `Frequente` (filtrée sur l'année active `AnneeAcademique.actif`) → `Salle` → `Classe`.
- **Notation** : `App\Support\Notation::mention()` — barème francophone/bilingue (EX/TB/B/AB/P/F) ou anglophone (A+/A/B+/B/C/D/F) selon `Classe.section`.
- **Bulletins** : page HTML autonome imprimée via `window.print()` (`@media print` + `@page { size: A4 }`), partagée entre les espaces admin, scolarité, parent et élève.
- **Emploi du temps** : grille construite par `EmploiDuTempsController::buildGrille()` et rendue par le composant lecture seule `<x-grille-edt>`, réutilisé par tous les espaces.
- **Clés primaires non standard** : la plupart des modèles n'utilisent pas `id` (ex. `Eleve.matricule`, `Personne.idPers`, `Classe.idClasse`). Toujours vérifier `$primaryKey`.

## Périmètre réalisé

✅ Auth + 6 rôles & espaces isolés
✅ MCD complet migré (33 tables)
✅ CRUD admin complet
✅ Espaces Scolarité & Finance
✅ Espaces Enseignant / Parent / Élève complets
✅ Affectation élève → classe (table `frequentes`)
✅ Année académique active
✅ Notation A+/A/B (anglophone) vs Excellent/TB/Bien (francophone) selon la section
✅ Bulletins (impression navigateur)
✅ Messagerie parents
✅ Présences / absences
✅ Emploi du temps interactif (+ configuration des horaires)
✅ Bibliothèque (catalogue + emprunts/retours avec gestion du stock)
✅ Vie scolaire (appréciations, remarques, convocations)
✅ Paramètres de l'établissement
✅ Gestion des utilisateurs (rôles + activation)

## Reste à faire

- Bulletins avec cachet / signature numérique
- Rapports & statistiques consolidés
