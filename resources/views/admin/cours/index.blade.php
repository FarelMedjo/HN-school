<x-admin-layout>
    <x-page-header title="Cours / Matières" :createRoute="route('admin.cours.create')" createLabel="Nouveau cours" />
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Libellé</th>
                    <th class="px-4 py-3">Classe</th>
                    <th class="px-4 py-3">Coef.</th>
                    <th class="px-4 py-3">Note max</th>
                    <th class="px-4 py-3">Enseignant</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($cours as $c)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $c->libelle }}</td>
                        <td class="px-4 py-3">{{ optional($c->classe)->libelle ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $c->coefficient }}</td>
                        <td class="px-4 py-3">{{ $c->note }}</td>
                        <td class="px-4 py-3">{{ optional($c->enseignant)->nom }} {{ optional($c->enseignant)->prenom }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.cours.edit', $c) }}" class="text-sky-600 hover:underline">Éditer</a>
                            <form method="POST" action="{{ route('admin.cours.destroy', $c) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun cours.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $cours->links() }}</div>
</x-admin-layout>
