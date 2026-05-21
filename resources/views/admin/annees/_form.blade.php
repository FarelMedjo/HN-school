@props(['annee', 'action', 'method' => 'POST'])

<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-xl">
    @csrf
    @if (strtoupper($method) !== 'POST') @method($method) @endif
    <div>
        <label class="block text-sm font-medium text-gray-700">Libellé * (ex: 2025-2026)</label>
        <input type="text" name="libelle" value="{{ old('libelle', $annee->libelle) }}" required
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Période</label>
        <input type="text" name="periode" value="{{ old('periode', $annee->periode) }}"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <label class="inline-flex items-center gap-2">
        <input type="hidden" name="actif" value="0">
        <input type="checkbox" name="actif" value="1" {{ old('actif', $annee->actif) ? 'checked' : '' }}>
        <span class="text-sm">Année active</span>
    </label>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        <a href="{{ route('admin.annees.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
    </div>
</form>
