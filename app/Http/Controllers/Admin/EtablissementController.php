<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EtablissementController extends Controller
{
    /** Clés de paramètres gérées ici qui pointent vers un fichier image. */
    private const FICHIERS = ['cachet', 'signature'];

    public function edit()
    {
        $directeurNom = Parametre::get('directeur_nom');
        $cachetUrl    = Parametre::fichierUrl('cachet');
        $signatureUrl = Parametre::fichierUrl('signature');

        return view('admin.etablissement.edit', compact('directeurNom', 'cachetUrl', 'signatureUrl'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'directeur_nom'     => ['nullable', 'string', 'max:150'],
            'cachet'            => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'signature'         => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        Parametre::set('directeur_nom', $request->input('directeur_nom'));

        foreach (self::FICHIERS as $cle) {
            // Suppression demandée
            if ($request->boolean("supprimer_{$cle}")) {
                $this->supprimerFichier($cle);
                continue;
            }

            // Nouveau fichier téléversé → remplace l'ancien
            if ($request->hasFile($cle)) {
                $this->supprimerFichier($cle);
                $path = $request->file($cle)->store('etablissement', 'public');
                Parametre::set($cle, $path);
            }
        }

        return redirect()
            ->route('admin.etablissement.edit')
            ->with('success', 'Paramètres de l\'établissement enregistrés.');
    }

    private function supprimerFichier(string $cle): void
    {
        $path = Parametre::get($cle);
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        Parametre::set($cle, null);
    }
}
