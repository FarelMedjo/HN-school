# HN-School

Application web Laravel de gestion d'école : crèche, maternelle, primaire (anglophone, francophone, bilingue), avec gestion des élèves, parents, enseignants, classes, paiements, scolarités, évaluations, etc.

## Stack

- **Laravel 13** (PHP 8.3)
- **MySQL** (Laragon)
- **Laravel Breeze** (auth Blade)
- **Spatie Laravel Permission** (rôles & permissions)
- **Tailwind CSS + Alpine.js + Vite**

## Rôles

- `admin` — administration complète
- `enseignant` — espace enseignant (à enrichir)
- `parent` — espace parent (à enrichir)
- `eleve` — espace élève (à enrichir)

## Comptes de démonstration

| Rôle        | Email                       | Mot de passe |
|-------------|-----------------------------|--------------|
| Admin       | admin@hnschool.test         | password     |
| Enseignant  | enseignant@hnschool.test    | password     |
| Parent      | parent@hnschool.test        | password     |
| Élève       | eleve@hnschool.test         | password     |

## Installation

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

# 5. Migrer + seeder
php artisan migrate:fresh --seed

# 6. Build assets
npm run build

# 7. Démarrer
php artisan serve
```

Ouvrir http://127.0.0.1:8000 et se connecter avec un compte démo.

## Modèle de données

31 tables fidèles au MCD fourni, regroupées en lots :

- **Référentiels** : `cycles`, `classes`, `salles`, `jour_semaines`, `tranches`, `scolarites`, `mode_paiements`, `nature_epreuves`, `disciplines`, `quartiers`, `ville_naissances`, `specialites`
- **Académique** : `annee_academiques`, `trimestres`, `session_examens`, `cours`, `emploi_du_temps`, `epreuves`
- **Personnes** : `personnes`, `admins`, `enseignants`, `eleves`, `parent_eleves`, `residents`, `titulaires`
- **Activités** : `frequentes`, `evaluations`, `rapports`, `paiements`, `messages`, `livres`

## Modules administration

CRUD complet pour : Années académiques, Cycles, Classes, Salles, Élèves (avec affectation classe), Enseignants, Parents, Cours/Matières, Paiements, Scolarités, Évaluations.

## Périmètre actuel (fondations)

✅ Auth + 4 rôles  
✅ MCD complet migré  
✅ CRUD admin de base  
✅ Dashboards par rôle (admin enrichi, autres minimaux)  
✅ Affectation élève → classe (table `frequentes`)  
✅ Année académique active

## Hors scope (post-évaluation)

- Bulletins PDF avec cachet/signature
- Messagerie parents (table `messages` prête)
- Présences/absences détaillées
- Emploi du temps interactif
- Notation A/A+/B (anglophone) vs Passable/A.Bien (francophone)
- Module bibliothèque (table `livres` prête)
- Convocations & appréciations
