@props(['livre', 'action', 'method' => 'POST', 'cancelUrl' => null])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-2xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif
    <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Titre *</label>
            <input type="text" name="titre" value="{{ old('titre', $livre->titre) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Auteur</label>
            <input type="text" name="auteur" value="{{ old('auteur', $livre->auteur) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Éditeur</label>
            <input type="text" name="editeur" value="{{ old('editeur', $livre->editeur) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">ISBN</label>
            <input type="text" name="isbn" value="{{ old('isbn', $livre->isbn) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Catégorie</label>
            <input type="text" name="categorie" value="{{ old('categorie', $livre->categorie) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Année d'édition</label>
            <input type="number" name="anneeEdition" value="{{ old('anneeEdition', $livre->anneeEdition) }}"
                   min="1000" max="{{ date('Y') + 1 }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Nombre d'exemplaires *</label>
            <input type="number" name="quantiteTotal" value="{{ old('quantiteTotal', $livre->quantiteTotal ?? 1) }}"
                   min="1" max="9999" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <p class="mt-1 text-xs text-gray-400">Les exemplaires déjà empruntés restent décomptés.</p>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $livre->description) }}</textarea>
        </div>
    </div>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ $cancelUrl ?? route('admin.livres.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
