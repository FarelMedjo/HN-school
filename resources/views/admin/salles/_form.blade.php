@props(['salle', 'classes', 'action', 'method' => 'POST', 'cancelUrl' => null])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Libellé *</label>
            <input type="text" name="libelle" value="{{ old('libelle', $salle->libelle) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Position</label>
            <input type="text" name="position" value="{{ old('position', $salle->position) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Surface</label>
            <input type="text" name="surface" value="{{ old('surface', $salle->surface) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Classe</label>
            <select name="idClasse" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">—</option>
                @foreach ($classes as $cl)
                    <option value="{{ $cl->idClasse }}" @selected(old('idClasse', $salle->idClasse) == $cl->idClasse)>{{ $cl->libelle }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <label class="inline-flex items-center gap-2">
        <input type="hidden" name="actif" value="0">
        <input type="checkbox" name="actif" value="1" {{ old('actif', $salle->actif ?? true) ? 'checked' : '' }}>
        <span class="text-sm">Active</span>
    </label>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ $cancelUrl ?? route('admin.salles.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
