@props(['cycle', 'action', 'method' => 'POST', 'cancelUrl' => null])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif

    <div>
        <label class="block text-sm font-medium text-gray-700">Libellé *</label>
        <input type="text" name="libelle" value="{{ old('libelle', $cycle->libelle) }}" required
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $cycle->description) }}</textarea>
    </div>

    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ $cancelUrl ?? route('admin.cycles.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
