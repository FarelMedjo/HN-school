@props(['classe', 'cycles', 'action', 'method' => 'POST'])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif
    <div>
        <label class="block text-sm font-medium text-gray-700">Libellé *</label>
        <input type="text" name="libelle" value="{{ old('libelle', $classe->libelle) }}" required
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Cycle</label>
        <select name="idCycle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach ($cycles as $cy)
                <option value="{{ $cy->idCycle }}" @selected(old('idCycle', $classe->idCycle) == $cy->idCycle)>{{ $cy->libelle }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ route('admin.classes.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
