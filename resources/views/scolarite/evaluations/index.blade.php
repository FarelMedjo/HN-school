<x-scolarite-layout>
    <x-page-header title="Évaluations" :createRoute="route('scolarite.evaluations.create')" createLabel="Nouvelle évaluation" />
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Cours</th>
                    <th class="px-4 py-3">Note /20</th>
                    <th class="px-4 py-3">Mention</th>
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
                        @php
                            $section = optional(optional($e->cours)->classe)->section ?? 'francophone';
                            $m = \App\Support\Notation::mention($e->note, $section);
                        @endphp
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs bg-{{ $m['color'] }}-100 text-{{ $m['color'] }}-700">
                                <strong>{{ $m['code'] }}</strong> · {{ $m['libelle'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $e->appreciation }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('scolarite.evaluations.destroy', $e) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune évaluation.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $evaluations->links() }}</div>
</x-scolarite-layout>
