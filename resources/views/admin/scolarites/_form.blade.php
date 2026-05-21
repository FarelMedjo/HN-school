@props(['scolarite', 'cycles', 'action', 'method' => 'POST'])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-2xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Cycle</label>
            <select name="idCycle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">—</option>
                @foreach ($cycles as $c)
                    <option value="{{ $c->idCycle }}" @selected(old('idCycle', $scolarite->idCycle) == $c->idCycle)>{{ $c->libelle }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Nb tranches *</label>
            <input type="number" name="nbreTranche" value="{{ old('nbreTranche', $scolarite->nbreTranche ?? 3) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Inscription *</label>
            <input type="number" step="100" name="inscription" value="{{ old('inscription', $scolarite->inscription ?? 0) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Pension *</label>
            <input type="number" step="100" name="pension" value="{{ old('pension', $scolarite->pension ?? 0) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="2"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $scolarite->description) }}</textarea>
        </div>
    </div>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ route('admin.scolarites.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
