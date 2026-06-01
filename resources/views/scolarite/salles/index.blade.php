<x-scolarite-layout>
    <x-page-header title="Salles" :createRoute="route('scolarite.salles.create')" createLabel="Nouvelle salle" />
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Libellé</th>
                    <th class="px-4 py-3">Position</th>
                    <th class="px-4 py-3">Surface</th>
                    <th class="px-4 py-3">Classe</th>
                    <th class="px-4 py-3">Actif</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($salles as $s)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $s->libelle }}</td>
                        <td class="px-4 py-3">{{ $s->position }}</td>
                        <td class="px-4 py-3">{{ $s->surface }}</td>
                        <td class="px-4 py-3">{{ optional($s->classe)->libelle ?? '—' }}</td>
                        <td class="px-4 py-3">{!! $s->actif ? '✅' : '—' !!}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('scolarite.salles.edit', $s) }}" class="text-sky-600 hover:underline">Éditer</a>
                            <form method="POST" action="{{ route('scolarite.salles.destroy', $s) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune salle.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $salles->links() }}</div>
</x-scolarite-layout>
