<x-admin-layout>
    <x-page-header title="Élèves" :createRoute="route('admin.eleves.create')" createLabel="Nouvel élève" />

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou prénom..."
               class="border-gray-300 rounded-md shadow-sm w-80">
        <button class="px-4 py-2 bg-slate-700 text-white rounded-md text-sm">Rechercher</button>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Matricule</th>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Prénom</th>
                    <th class="px-4 py-3">Naissance</th>
                    <th class="px-4 py-3">Sexe</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($eleves as $e)
                    <tr>
                        <td class="px-4 py-3">{{ str_pad($e->matricule, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 font-medium">{{ $e->nom }}</td>
                        <td class="px-4 py-3">{{ $e->prenom }}</td>
                        <td class="px-4 py-3">{{ optional($e->dateNaissance)->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $e->sexe == 0 ? 'M' : 'F' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.eleves.show', $e) }}" class="text-emerald-600 hover:underline">Voir</a>
                            <a href="{{ route('admin.eleves.edit', $e) }}" class="text-sky-600 hover:underline">Éditer</a>
                            <form method="POST" action="{{ route('admin.eleves.destroy', $e) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun élève.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $eleves->links() }}</div>
</x-admin-layout>
