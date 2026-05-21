<x-admin-layout>
    <x-page-header title="Enseignants" :createRoute="route('admin.enseignants.create')" createLabel="Nouvel enseignant" />
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Prénom</th>
                    <th class="px-4 py-3">Cours</th>
                    <th class="px-4 py-3">Mobile</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($enseignants as $e)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ optional($e->personne)->nom }}</td>
                        <td class="px-4 py-3">{{ optional($e->personne)->prenom }}</td>
                        <td class="px-4 py-3">{{ optional($e->cours)->libelle ?? '—' }}</td>
                        <td class="px-4 py-3">{{ optional($e->personne)->mobile ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.enseignants.destroy', $e) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucun enseignant.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $enseignants->links() }}</div>
</x-admin-layout>
