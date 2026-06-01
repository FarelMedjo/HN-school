<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Emprunt;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmpruntController extends Controller
{
    public function index(Request $request)
    {
        $statut = $request->input('statut');

        $emprunts = Emprunt::with(['livre', 'eleve'])
            ->when($statut === 'en_cours', fn ($q) => $q->where('statut', 'en_cours'))
            ->when($statut === 'rendu', fn ($q) => $q->where('statut', 'rendu'))
            ->when($statut === 'retard', fn ($q) => $q->where('statut', 'en_cours')
                ->whereDate('dateRetourPrevue', '<', today()))
            ->orderByDesc('idEmprunt')
            ->paginate(20)
            ->withQueryString();

        return view('admin.emprunts.index', compact('emprunts', 'statut'));
    }

    public function create()
    {
        return view('admin.emprunts.create', [
            'emprunt' => new Emprunt(),
            'livres' => Livre::where('quantiteDisponible', '>', 0)->orderBy('titre')->get(),
            'eleves' => Eleve::orderBy('nom')->orderBy('prenom')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idLivre' => ['required', 'exists:livres,idLivre'],
            'matricule' => ['required', 'exists:eleves,matricule'],
            'dateEmprunt' => ['required', 'date'],
            'dateRetourPrevue' => ['required', 'date', 'after_or_equal:dateEmprunt'],
            'remarque' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($data) {
            $livre = Livre::where('idLivre', $data['idLivre'])->lockForUpdate()->firstOrFail();

            if ($livre->quantiteDisponible < 1) {
                throw ValidationException::withMessages(['idLivre' => 'Ce livre n\'est plus disponible.']);
            }

            Emprunt::create([
                'idLivre' => $data['idLivre'],
                'matricule' => $data['matricule'],
                'dateEmprunt' => $data['dateEmprunt'],
                'dateRetourPrevue' => $data['dateRetourPrevue'],
                'statut' => 'en_cours',
                'remarque' => $data['remarque'] ?? null,
                'idAdmin' => auth()->id(),
            ]);

            $livre->decrement('quantiteDisponible');
        });

        return redirect()->route('admin.emprunts.index')->with('success', 'Emprunt enregistré.');
    }

    public function retour(Emprunt $emprunt)
    {
        if ($emprunt->statut === 'rendu') {
            return back()->withErrors(['emprunt' => 'Cet emprunt est déjà rendu.']);
        }

        DB::transaction(function () use ($emprunt) {
            $emprunt->update([
                'statut' => 'rendu',
                'dateRetourReelle' => today(),
            ]);

            $livre = Livre::where('idLivre', $emprunt->idLivre)->lockForUpdate()->first();
            if ($livre) {
                $livre->quantiteDisponible = min($livre->quantiteTotal, $livre->quantiteDisponible + 1);
                $livre->save();
            }
        });

        return back()->with('success', 'Retour enregistré.');
    }

    public function destroy(Emprunt $emprunt)
    {
        DB::transaction(function () use ($emprunt) {
            if ($emprunt->statut === 'en_cours') {
                $livre = Livre::where('idLivre', $emprunt->idLivre)->lockForUpdate()->first();
                if ($livre) {
                    $livre->quantiteDisponible = min($livre->quantiteTotal, $livre->quantiteDisponible + 1);
                    $livre->save();
                }
            }
            $emprunt->delete();
        });

        return back()->with('success', 'Emprunt supprimé.');
    }
}
