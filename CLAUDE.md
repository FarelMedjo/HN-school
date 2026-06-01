# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Environment (Windows / Laragon)

All `php` and `artisan` commands must use the full Laragon path:

```powershell
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan <command>
```

Always use **PowerShell**, not Bash, for running commands on this machine.

## Common Commands

```powershell
# Dev server + queue + logs + Vite (all-in-one)
composer run dev

# Migrations
php artisan migrate
php artisan migrate:fresh --seed   # full reset with demo data

# Seeding only
php artisan db:seed --class=DemoDataSeeder
php artisan db:seed --class=RolesAndAdminSeeder

# Asset compilation
npm run dev    # watch mode
npm run build  # production

# View cache
php artisan view:clear
php artisan view:cache

# Route inspection
php artisan route:list --name=admin
php artisan route:list --name=student

# Code style (Laravel Pint)
./vendor/bin/pint

# Tests
composer test
php artisan test --filter=TestClassName
```

## Architecture Overview

### Role-Based Spaces

The `/dashboard` route auto-redirects to the correct space based on Spatie role. Four isolated spaces:

| Role | Prefix | Controller namespace | Layout | Sidebar colour |
|------|--------|---------------------|--------|---------------|
| `admin` | `/admin` | `Admin\*` | `x-admin-layout` | blue-950 |
| `enseignant` | `/teacher` | `Teacher\TeacherController` | `x-teacher-layout` | indigo-950 |
| `parent` | `/parent` | `Tuteur\TuteurController` | `x-parent-layout` | teal-950 |
| `eleve` | `/student` | `Student\StudentController` | `x-student-layout` | purple-950 |

> **`parent` is a PHP reserved keyword** — the parent role's controller lives in `App\Http\Controllers\Tuteur\TuteurController`.

Each layout has a matching PHP component in `app/View/Components/` and a full-page Blade file in `resources/views/layouts/`.

### User → Person → Domain identity chain

`users` table has two bridge columns added by migration:
- `users.idPers` → `personnes.idPers` (links teachers and parents to their `Personne` record)
- `users.matricule` → `eleves.matricule` (links student accounts to their `Eleve` record)

```
User::eleve()      → Eleve      (via users.matricule)
User::personne()   → Personne   (via users.idPers)
Personne::coursEnseignes()  → Cours[]
Personne::parentEleves()    → ParentEleve[] → Eleve[]
```

### Finding a student's class (the standard pattern)

There is no direct `eleve.idClasse`. Class membership is resolved through:

```
Frequente (filtered by active AnneeAcademique.actif=true)
  → Salle → Classe
```

The active year is always: `AnneeAcademique::where('actif', true)->first()`

This lookup appears in every controller space; copy the pattern from `TuteurController::classeActuelle()`.

### Shared timetable grid

`EmploiDuTempsController` holds:
- `const JOURS`, `const HEURES`, `const COULEURS` — used in both the admin interactive grid and the shared read-only component
- `static buildGrille(int $idClasse): array` — returns `['grille' => ..., 'palette' => ...]`, called by teacher/parent/student controllers

The read-only Blade component `<x-grille-edt :grille :palette :classeLibelle />` is defined in `resources/views/components/grille-edt.blade.php`.

### Notation system

`App\Support\Notation::mention(?float $note, string $section): array` returns `['code', 'libelle', 'color']`.

- **Francophone / bilingue**: EX ≥16 / TB ≥14 / B ≥12 / AB ≥10 / P ≥8 / F
- **Anglophone**: A+ ≥18 / A ≥16 / B+ ≥14 / B ≥12 / C ≥10 / D ≥8 / F

`$section` comes from `Classe.section` (values: `francophone`, `anglophone`, `bilingue`). Bilingue falls back to francophone grading.

### Bulletins

Bulletins render via **browser `window.print()`** (no DomPDF). The view `resources/views/bulletins/show.blade.php` is a standalone HTML page (no layout wrapper) with `@media print` + `@page { size: A4 }` CSS. Both `BulletinController::show()` (admin) and `TuteurController::bulletin()` / `StudentController::bulletinShow()` delegate to the same controller method.

Notes for a bulletin are filtered through `SessionExamen.idTrimestre` → `Evaluation.idSession`. If no session exists for the trimestre, it falls back to all evaluations for those courses.

### Messagerie

- `messages.idParent` stores `Personne.idPers` of the recipient parent (not a `users.id`)
- `messages.idExp_Pers` stores sender's `Personne.idPers` (nullable for system messages)
- `messages.valider` = `false` → unread, `true` → read (cast to boolean in the model)
- The parent unread badge is computed in the `layouts/parent.blade.php` `@php` block

### Bibliothèque (livres + emprunts)

Two tables: `livres` (catalogue) and `emprunts` (loans). Models `Livre` / `Emprunt` are `$guarded = []`.

- `Livre` tracks `quantiteTotal` and `quantiteDisponible`. **`quantiteDisponible` is the source of truth for availability** — it is decremented on loan and incremented on return, never derived on read.
- `Emprunt.statut` enum: `en_cours` / `rendu` / `retard`. The `Emprunt::en_retard` accessor is computed (`statut === 'en_cours' && dateRetourPrevue->lt(today())`) — there is no scheduled job flipping the stored `retard` value, so **filter/display on the `en_retard` accessor, not the stored enum**, for overdue state.
- `emprunts.matricule` → `eleves.matricule`; `emprunts.idLivre` → `livres.idLivre` (`onDelete('restrict')` — a book with loans can't be deleted).

Admin CRUD lives in `Admin\LivreController` (catalogue, `->except(['show'])`) and `Admin\EmpruntController` (`index`/`create`/`store`/`destroy` + a custom `PATCH emprunts/{emprunt}/retour`). Stock mutations (`store`, `retour`, `destroy`) run inside `DB::transaction` with `lockForUpdate` and throw a `ValidationException` if no copy is available; `retour` caps the increment at `quantiteTotal`.

Read-only loan history is exposed to families: `StudentController::emprunts()` (route `student.emprunts`, view `student.emprunts`) and `TuteurController::emprunts(Eleve)` (route `parent.enfant.emprunts`, view `parent.enfant.emprunts`). Both query `Emprunt::with('livre')->where('matricule', …)` — no actions, just the status badge driven by `en_retard`.

### Non-standard primary keys

Most models deviate from Laravel's default `id`. Always check the model's `$primaryKey`:

```
Eleve       → matricule (bigIncrements)
Personne    → idPers
Classe      → idClasse
Cours       → idCours
Salle       → idSalle
EmploiDuTemps → idTemps  (table: emploi_du_temps)
Frequente   → idFrequente
Evaluation  → idEval
Presence    → idPresence
Message     → idMessages
Livre       → idLivre
Emprunt     → idEmprunt
AnneeAcademique → idAnnee
Trimestre   → idTrimes
```

### Tailwind dynamic classes

Color classes are built dynamically from palette arrays (e.g. `bg-{{ $color }}-100`). If a new colour is added to `EmploiDuTempsController::COULEURS` or `Notation`, add it to `tailwind.config.js` safelist to prevent purging.

## Seeders

Two seeders run in sequence via `DatabaseSeeder`:

1. **`RolesAndAdminSeeder`** — creates 4 roles + 4 demo user accounts (all password: `password`)
2. **`DemoDataSeeder`** — creates cycles, classes, salles, année active, élèves, cours, presences, evaluations, messages, EDT slots, and links demo user accounts to their `Personne` / `Eleve` records via `idPers` / `matricule`

Demo accounts:

| Role | Email | Linked to |
|------|-------|-----------|
| admin | admin@hnschool.test | — |
| enseignant | enseignant@hnschool.test | Personne: Mballa Jean |
| parent | parent@hnschool.test | Personne: Kouam Marie (parent of Léa & Noé) |
| eleve | eleve@hnschool.test | Eleve: Kouam Léa (CP1 Francophone) |
