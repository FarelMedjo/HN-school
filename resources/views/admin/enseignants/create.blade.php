<x-admin-layout>
    <x-page-header title="Nouvel enseignant" />
    <form method="POST" action="{{ route('admin.enseignants.store') }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4 max-w-3xl">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom *</label>
                <input type="text" name="nom" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Prénom *</label>
                <input type="text" name="prenom" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mobile</label>
                <input type="text" name="mobile" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cours principal</label>
                <select name="idCours" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">—</option>
                    @foreach ($cours as $c)
                        <option value="{{ $c->idCours }}">{{ $c->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Username *</label>
                <input type="text" name="username" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mot de passe *</label>
                <input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
            <a href="{{ route('admin.enseignants.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
        </div>
    </form>
</x-admin-layout>
