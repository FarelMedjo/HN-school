<x-admin-layout>
    <x-page-header title="Nouvelle convocation" />

    <form method="POST" action="{{ route('admin.convocations.store') }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Élève *</label>
            <select name="matricule" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">— Choisir un élève —</option>
                @foreach ($eleves as $el)
                    <option value="{{ $el->matricule }}" @selected(old('matricule') == $el->matricule)>
                        {{ $el->nom }} {{ $el->prenom }} ({{ str_pad($el->matricule, 5, '0', STR_PAD_LEFT) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Objet *</label>
            <input type="text" name="objet" value="{{ old('objet') }}" required maxlength="255"
                   placeholder="Ex. Entretien concernant le comportement"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date et heure du rendez-vous *</label>
                <input type="datetime-local" name="dateRdv" value="{{ old('dateRdv', now()->addDays(3)->format('Y-m-d\TH:i')) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Lieu</label>
                <input type="text" name="lieu" value="{{ old('lieu', 'Secrétariat de l\'établissement') }}" maxlength="255"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Motif détaillé</label>
            <textarea name="motif" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('motif') }}</textarea>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer & imprimer</button>
            <a href="{{ route('admin.convocations.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
        </div>
    </form>
</x-admin-layout>
