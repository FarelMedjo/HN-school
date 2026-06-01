<x-admin-layout>
    <x-page-header title="Journal / Remarques" subtitle="Suivi daté du comportement et du travail des élèves" />

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Formulaire --}}
        <div class="bg-white rounded-lg shadow-sm p-5 h-fit">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Ajouter une remarque</h3>
            <form method="POST" action="{{ route('admin.remarques.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Élève *</label>
                    <select name="matricule" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Choisir —</option>
                        @foreach ($eleves as $el)
                            <option value="{{ $el->matricule }}" @selected(old('matricule', $matricule) == $el->matricule)>
                                {{ $el->nom }} {{ $el->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Date *</label>
                        <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Catégorie *</label>
                        <select name="categorie" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            @foreach (\App\Models\Remarque::CATEGORIES as $key => $cat)
                                <option value="{{ $key }}" @selected(old('categorie') == $key)>{{ $cat['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Remarque *</label>
                    <textarea name="contenu" rows="4" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('contenu') }}</textarea>
                </div>
                <button class="w-full py-2 bg-blue-950 text-white text-sm rounded-md hover:bg-blue-900">Enregistrer</button>
            </form>
        </div>

        {{-- Liste --}}
        <div class="lg:col-span-2">
            <form method="GET" class="mb-3 flex items-end gap-3">
                <div class="min-w-[220px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Filtrer par élève</label>
                    <select name="matricule" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Tous —</option>
                        @foreach ($eleves as $el)
                            <option value="{{ $el->matricule }}" @selected($matricule == $el->matricule)>{{ $el->nom }} {{ $el->prenom }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Élève</th>
                            <th class="px-4 py-3">Catégorie</th>
                            <th class="px-4 py-3">Remarque</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($remarques as $r)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $r->date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium whitespace-nowrap">{{ optional($r->eleve)->nom }} {{ optional($r->eleve)->prenom }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-{{ $r->categorie_color }}-100 text-{{ $r->categorie_color }}-700">
                                        {{ $r->categorie_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $r->contenu }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.remarques.destroy', $r) }}" class="inline"
                                          onsubmit="return confirm('Supprimer cette remarque ?')">
                                        @csrf @method('DELETE')
                                        <button class="text-rose-600 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucune remarque.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $remarques->links() }}</div>
        </div>
    </div>
</x-admin-layout>
