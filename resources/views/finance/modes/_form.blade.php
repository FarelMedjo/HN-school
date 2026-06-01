@props(['mode', 'action', 'method' => 'POST', 'cancelUrl' => null])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-2xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif
    <div>
        <label class="block text-sm font-medium text-gray-700">Libellé *</label>
        <input type="text" name="libelle" value="{{ old('libelle', $mode->libelle) }}" required
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Information</label>
        <textarea name="information" rows="2"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('information', $mode->information) }}</textarea>
    </div>
    <label class="inline-flex items-center gap-2">
        <input type="hidden" name="actif" value="0">
        <input type="checkbox" name="actif" value="1" @checked(old('actif', $mode->actif ?? true))
               class="rounded border-gray-300 text-emerald-600">
        <span class="text-sm text-gray-700">Actif</span>
    </label>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ $cancelUrl ?? route('finance.modes.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
