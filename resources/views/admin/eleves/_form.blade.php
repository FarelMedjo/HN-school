@props(['eleve', 'villes', 'action', 'method' => 'POST'])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-3xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nom *</label>
            <input type="text" name="nom" value="{{ old('nom', $eleve->nom) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Prénom *</label>
            <input type="text" name="prenom" value="{{ old('prenom', $eleve->prenom) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
            <input type="date" name="dateNaissance"
                   value="{{ old('dateNaissance', optional($eleve->dateNaissance)->format('Y-m-d')) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Lieu de naissance</label>
            <input type="text" name="lieuNaissance" value="{{ old('lieuNaissance', $eleve->lieuNaissance) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Sexe</label>
            <select name="sexe" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="0" @selected(old('sexe', $eleve->sexe) === 0 || $eleve->sexe === 0)>Masculin</option>
                <option value="1" @selected(old('sexe', $eleve->sexe) === 1 || $eleve->sexe === 1)>Féminin</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Langue</label>
            <input type="text" name="langue" value="{{ old('langue', $eleve->langue) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Ville de naissance</label>
            <select name="idVilleNaissance" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">—</option>
                @foreach ($villes as $v)
                    <option value="{{ $v->idVille }}" @selected(old('idVilleNaissance', $eleve->idVilleNaissance) == $v->idVille)>{{ $v->libelle }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ route('admin.eleves.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
