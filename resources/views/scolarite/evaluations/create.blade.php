<x-scolarite-layout>
    <x-page-header title="Nouvelle évaluation" />
    <form method="POST" action="{{ route('scolarite.evaluations.store') }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-2xl">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Élève *</label>
                <select name="matricule" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">—</option>
                    @foreach ($eleves as $e)
                        <option value="{{ $e->matricule }}">{{ $e->nom }} {{ $e->prenom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Cours *</label>
                <select name="idCours" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">—</option>
                    @foreach ($cours as $c)
                        <option value="{{ $c->idCours }}">{{ $c->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Note *</label>
                <input type="number" step="0.25" name="note" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Appréciation</label>
                <input type="text" name="appreciation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
            <a href="{{ route('scolarite.evaluations.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
        </div>
    </form>
</x-scolarite-layout>
