<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\EmploiDuTemps;
use App\Models\Horaire;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    public const JOURS  = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    /** Horaires par défaut si la table `horaires` est vide. */
    public const HEURES = ['07:30', '08:30', '09:30', '10:30', '11:30', '13:00', '14:00', '15:00', '16:00'];
    public const COULEURS = [
        'blue', 'emerald', 'violet', 'amber', 'rose', 'cyan', 'orange', 'teal', 'indigo', 'pink',
    ];

    /** Liste ordonnée des tranches horaires configurées (chaînes "HH:MM"). */
    public static function heures(): array
    {
        $heures = Horaire::orderBy('heure')->pluck('heure')->all();

        return $heures ?: self::HEURES;
    }

    public function index(Request $request)
    {
        $classes  = Classe::with('cycle')->orderBy('libelle')->get();
        $idClasse = $request->integer('idClasse') ?: $classes->first()?->idClasse;

        $cours = $idClasse
            ? Cours::where('idClasse', $idClasse)->orderBy('libelle')->get()
            : collect();

        // Grille indexée [jour][heure] → EmploiDuTemps
        $grille = EmploiDuTemps::where('idClasse', $idClasse)
            ->with('cours')
            ->get()
            ->groupBy('jour')
            ->map(fn($g) => $g->keyBy('heure'));

        // Palette de couleurs par cours
        $palette = $cours->values()->mapWithKeys(fn($c, $i) => [
            $c->idCours => self::COULEURS[$i % count(self::COULEURS)],
        ]);

        return view('admin.emploi-du-temps.index', compact(
            'classes', 'idClasse', 'cours', 'grille', 'palette'
        ));
    }

    public function store(Request $request)
    {
        $joursStr  = implode(',', self::JOURS);
        $heuresStr = implode(',', self::heures());

        $data = $request->validate([
            'idClasse' => ['required', 'exists:classes,idClasse'],
            'jour'     => ['required', "in:{$joursStr}"],
            'heure'    => ['required', "in:{$heuresStr}"],
            'idCours'  => ['required', 'exists:cours,idCours'],
        ]);

        EmploiDuTemps::updateOrCreate(
            ['idClasse' => $data['idClasse'], 'jour' => $data['jour'], 'heure' => $data['heure']],
            ['idCours'  => $data['idCours'],  'idAdmin' => auth()->id()]
        );

        return redirect()
            ->route('admin.emploi-du-temps.index', ['idClasse' => $data['idClasse']])
            ->with('success', 'Créneau enregistré.');
    }

    public function destroy(EmploiDuTemps $emploiDuTemp)
    {
        $idClasse = $emploiDuTemp->idClasse;
        $emploiDuTemp->delete();

        return redirect()
            ->route('admin.emploi-du-temps.index', ['idClasse' => $idClasse])
            ->with('success', 'Créneau supprimé.');
    }

    /** Page de réglages des tranches horaires. */
    public function horaires()
    {
        $horaires = Horaire::orderBy('heure')->get();

        // Nombre de créneaux occupant chaque heure (pour bloquer la suppression).
        $usage = EmploiDuTemps::selectRaw('heure, COUNT(*) as total')
            ->groupBy('heure')
            ->pluck('total', 'heure');

        return view('admin.emploi-du-temps.horaires', compact('horaires', 'usage'));
    }

    public function storeHoraire(Request $request)
    {
        $data = $request->validate([
            'heure' => ['required', 'date_format:H:i', 'unique:horaires,heure'],
        ]);

        Horaire::create(['heure' => $data['heure']]);

        return redirect()
            ->route('admin.horaires.index')
            ->with('success', "Tranche horaire {$data['heure']} ajoutée.");
    }

    public function destroyHoraire(Horaire $horaire)
    {
        $enUsage = EmploiDuTemps::where('heure', $horaire->heure)->exists();

        if ($enUsage) {
            return redirect()
                ->route('admin.horaires.index')
                ->with('error', "Impossible de supprimer {$horaire->heure} : des créneaux y sont rattachés.");
        }

        $horaire->delete();

        return redirect()
            ->route('admin.horaires.index')
            ->with('success', "Tranche horaire {$horaire->heure} supprimée.");
    }

    /** Méthode statique pour réutilisation dans les espaces enseignant/parent */
    public static function buildGrille(int $idClasse): array
    {
        $cours = Cours::where('idClasse', $idClasse)->orderBy('libelle')->get();

        $grille = EmploiDuTemps::where('idClasse', $idClasse)
            ->with('cours')
            ->get()
            ->groupBy('jour')
            ->map(fn($g) => $g->keyBy('heure'));

        $palette = $cours->values()->mapWithKeys(fn($c, $i) => [
            $c->idCours => self::COULEURS[$i % count(self::COULEURS)],
        ]);

        return compact('grille', 'palette');
    }
}
