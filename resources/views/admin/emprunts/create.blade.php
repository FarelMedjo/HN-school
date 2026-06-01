<x-admin-layout>
    <x-page-header title="Nouvel emprunt" />

    @if ($livres->isEmpty())
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-md p-4 max-w-2xl">
            Aucun livre disponible à l'emprunt.
            <a href="{{ route('admin.livres.index') }}" class="underline">Voir le catalogue</a>.
        </div>
    @else
        <form method="POST" action="{{ route('admin.emprunts.store') }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-2xl">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Livre *</label>
                <select name="idLivre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">— Choisir un livre —</option>
                    @foreach ($livres as $l)
                        <option value="{{ $l->idLivre }}" @selected(old('idLivre') == $l->idLivre)>
                            {{ $l->titre }} ({{ $l->quantiteDisponible }} dispo)
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Élève *</label>
                <select name="matricule" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">— Choisir un élève —</option>
                    @foreach ($eleves as $el)
                        <option value="{{ $el->matricule }}" @selected(old('matricule') == $el->matricule)>
                            {{ $el->nom }} {{ $el->prenom }} ({{ str_pad($el->matricule, 5, '0', STR_PAD_LEFT) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date d'emprunt *</label>
                    <input type="date" name="dateEmprunt" value="{{ old('dateEmprunt', now()->toDateString()) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Retour prévu *</label>
                    <input type="date" name="dateRetourPrevue" value="{{ old('dateRetourPrevue', now()->addDays(14)->toDateString()) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Remarque</label>
                <textarea name="remarque" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('remarque') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer l'emprunt</button>
                <a href="{{ route('admin.emprunts.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
            </div>
        </form>
    @endif
</x-admin-layout>
