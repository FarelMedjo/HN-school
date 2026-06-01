<x-finance-layout>
    <x-page-header title="Nouveau paiement" />

    <form method="POST" action="{{ route('finance.paiements.store') }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-3xl">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Élève *</label>
                <select name="matricule" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">—</option>
                    @foreach ($eleves as $e)
                        <option value="{{ $e->matricule }}">{{ $e->nom }} {{ $e->prenom }} (#{{ $e->matricule }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Année académique *</label>
                <select name="idAca" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">—</option>
                    @foreach ($annees as $a)
                        <option value="{{ $a->idAnnee }}" @selected($a->actif)>{{ $a->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date *</label>
                <input type="date" name="datePaie" value="{{ now()->format('Y-m-d') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Montant (XAF) *</label>
                <input type="number" step="100" name="montant" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mode</label>
                <select name="idMode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">—</option>
                    @foreach ($modes as $m)
                        <option value="{{ $m->idMode }}">{{ $m->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Commentaire</label>
                <input type="text" name="commentaire"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Référence opération</label>
                <input type="text" name="operation_ID"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
            <a href="{{ route('finance.paiements.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
        </div>
    </form>
</x-finance-layout>
