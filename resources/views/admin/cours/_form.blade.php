@props(['cours', 'classes', 'enseignants', 'action', 'method' => 'POST'])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-3xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif
    <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Libellé *</label>
            <input type="text" name="libelle" value="{{ old('libelle', $cours->libelle) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Note max</label>
            <input type="number" step="0.5" name="note" value="{{ old('note', $cours->note ?? 20) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Coefficient</label>
            <input type="number" step="0.5" name="coefficient" value="{{ old('coefficient', $cours->coefficient ?? 1) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Classe</label>
            <select name="idClasse" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">—</option>
                @foreach ($classes as $cl)
                    <option value="{{ $cl->idClasse }}" @selected(old('idClasse', $cours->idClasse) == $cl->idClasse)>{{ $cl->libelle }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Enseignant</label>
            <select name="idPers" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">—</option>
                @foreach ($enseignants as $e)
                    <option value="{{ $e->idPers }}" @selected(old('idPers', $cours->idPers) == $e->idPers)>{{ $e->nom }} {{ $e->prenom }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="2"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $cours->description) }}</textarea>
        </div>
    </div>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ route('admin.cours.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
