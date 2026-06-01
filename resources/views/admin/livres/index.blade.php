<x-admin-layout>
    <x-page-header title="Catalogue — Livres" :createRoute="route('admin.livres.create')" createLabel="Nouveau livre" />

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ $q }}" placeholder="Titre, auteur, catégorie, ISBN..."
               class="border-gray-300 rounded-md shadow-sm w-80">
        <button class="px-4 py-2 bg-slate-700 text-white rounded-md text-sm">Rechercher</button>
        @if ($q !== '')
            <a href="{{ route('admin.livres.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm">Réinitialiser</a>
        @endif
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Titre</th>
                    <th class="px-4 py-3">Auteur</th>
                    <th class="px-4 py-3">Catégorie</th>
                    <th class="px-4 py-3">ISBN</th>
                    <th class="px-4 py-3">Année</th>
                    <th class="px-4 py-3">Disponibles</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($livres as $l)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $l->titre }}</td>
                        <td class="px-4 py-3">{{ $l->auteur ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $l->categorie ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $l->isbn ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $l->anneeEdition ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $l->quantiteDisponible > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $l->quantiteDisponible }} / {{ $l->quantiteTotal }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.livres.edit', $l) }}" class="text-sky-600 hover:underline">Éditer</a>
                            <form method="POST" action="{{ route('admin.livres.destroy', $l) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ce livre ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucun livre.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $livres->links() }}</div>
</x-admin-layout>
