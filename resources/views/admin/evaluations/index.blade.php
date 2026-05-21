<x-admin-layout>
    <x-page-header title="Évaluations" :createRoute="route('admin.evaluations.create')" createLabel="Nouvelle évaluation" />
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Cours</th>
                    <th class="px-4 py-3">Note</th>
                    <th class="px-4 py-3">Appréciation</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($evaluations as $e)
                    <tr>
                        <td class="px-4 py-3">{{ optional($e->eleve)->nom }} {{ optional($e->eleve)->prenom }}</td>
                        <td class="px-4 py-3">{{ optional($e->cours)->libelle }}</td>
                        <td class="px-4 py-3 font-medium">{{ $e->note }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $e->appreciation }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.evaluations.destroy', $e) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucune évaluation.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $evaluations->links() }}</div>
</x-admin-layout>
