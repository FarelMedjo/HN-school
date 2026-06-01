<x-admin-layout>
    <x-page-header title="Appréciations" subtitle="Commentaire global par élève et par trimestre (affiché sur le bulletin)" />

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Formulaire --}}
        <div class="bg-white rounded-lg shadow-sm p-5 h-fit">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Saisir une appréciation</h3>
            <form method="POST" action="{{ route('admin.appreciations.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Élève *</label>
                    <select name="matricule" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Choisir —</option>
                        @foreach ($eleves as $el)
                            <option value="{{ $el->matricule }}" @selected(old('matricule') == $el->matricule)>
                                {{ $el->nom }} {{ $el->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Trimestre *</label>
                    <select name="idTrimes" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Choisir —</option>
                        @foreach ($trimestres as $t)
                            <option value="{{ $t->idTrimes }}" @selected(old('idTrimes') == $t->idTrimes)>
                                {{ $t->libelle }}{{ $t->annee ? ' — ' . $t->annee->libelle : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Appréciation *</label>
                    <textarea name="contenu" rows="4" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('contenu') }}</textarea>
                </div>
                <button class="w-full py-2 bg-blue-950 text-white text-sm rounded-md hover:bg-blue-900">Enregistrer</button>
                <p class="text-xs text-gray-400">Une appréciation existante pour le même élève / trimestre est remplacée.</p>
            </form>
        </div>

        {{-- Liste --}}
        <div class="lg:col-span-2">
            <form method="GET" class="mb-3 flex items-end gap-3">
                <div class="min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Filtrer par trimestre</label>
                    <select name="idTrimes" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Tous —</option>
                        @foreach ($trimestres as $t)
                            <option value="{{ $t->idTrimes }}" @selected($idTrimes == $t->idTrimes)>{{ $t->libelle }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Élève</th>
                            <th class="px-4 py-3">Trimestre</th>
                            <th class="px-4 py-3">Appréciation</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($appreciations as $a)
                            <tr>
                                <td class="px-4 py-3 font-medium whitespace-nowrap">{{ optional($a->eleve)->nom }} {{ optional($a->eleve)->prenom }}</td>
                                <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ optional($a->trimestre)->libelle ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $a->contenu }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.appreciations.destroy', $a) }}" class="inline"
                                          onsubmit="return confirm('Supprimer cette appréciation ?')">
                                        @csrf @method('DELETE')
                                        <button class="text-rose-600 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucune appréciation.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $appreciations->links() }}</div>
        </div>
    </div>
</x-admin-layout>
