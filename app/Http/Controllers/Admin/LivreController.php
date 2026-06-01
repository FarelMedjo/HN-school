<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));

        $livres = Livre::query()
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('titre', 'like', "%{$q}%")
                    ->orWhere('auteur', 'like', "%{$q}%")
                    ->orWhere('categorie', 'like', "%{$q}%")
                    ->orWhere('isbn', 'like', "%{$q}%");
            }))
            ->orderBy('titre')
            ->paginate(15)
            ->withQueryString();

        return view('admin.livres.index', compact('livres', 'q'));
    }

    public function create()
    {
        return view('admin.livres.create', ['livre' => new Livre()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['quantiteDisponible'] = $data['quantiteTotal'];
        Livre::create($data);

        return redirect()->route('admin.livres.index')->with('success', 'Livre ajouté au catalogue.');
    }

    public function edit(Livre $livre)
    {
        return view('admin.livres.edit', compact('livre'));
    }

    public function update(Request $request, Livre $livre)
    {
        $data = $this->validateData($request);

        // On garde le nombre d'exemplaires déjà sortis : disponible = total - empruntés.
        $empruntes = $livre->quantiteTotal - $livre->quantiteDisponible;
        $data['quantiteDisponible'] = max(0, $data['quantiteTotal'] - $empruntes);

        $livre->update($data);

        return redirect()->route('admin.livres.index')->with('success', 'Livre mis à jour.');
    }

    public function destroy(Livre $livre)
    {
        if ($livre->emprunts()->where('statut', 'en_cours')->exists()) {
            return back()->withErrors(['livre' => 'Impossible de supprimer : des exemplaires sont encore empruntés.']);
        }

        $livre->delete();

        return redirect()->route('admin.livres.index')->with('success', 'Livre supprimé.');
    }

    private function validateData(Request $r): array
    {
        $idLivre = $r->route('livre')?->idLivre;

        return $r->validate([
            'titre' => ['required', 'string', 'max:200'],
            'auteur' => ['nullable', 'string', 'max:150'],
            'editeur' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'max:20', "unique:livres,isbn,{$idLivre},idLivre"],
            'anneeEdition' => ['nullable', 'integer', 'min:1000', 'max:'.(date('Y') + 1)],
            'categorie' => ['nullable', 'string', 'max:80'],
            'quantiteTotal' => ['required', 'integer', 'min:1', 'max:9999'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
