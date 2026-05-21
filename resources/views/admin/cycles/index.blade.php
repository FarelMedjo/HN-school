<x-admin-layout>
    <x-page-header title="Cycles" subtitle="Niveaux scolaires de l'établissement"
                   :createRoute="route('admin.cycles.create')" createLabel="Nouveau cycle" />

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Libellé</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Classes</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($cycles as $c)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $c->libelle }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($c->description, 60) }}</td>
                        <td class="px-4 py-3">{{ $c->classes_count }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.cycles.edit', $c) }}" class="text-sky-600 hover:underline">Éditer</a>
                            <form method="POST" action="{{ route('admin.cycles.destroy', $c) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ce cycle ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucun cycle.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $cycles->links() }}</div>
</x-admin-layout>
