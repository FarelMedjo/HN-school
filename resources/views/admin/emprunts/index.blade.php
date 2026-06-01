<x-admin-layout>
    <x-page-header title="Emprunts" :createRoute="route('admin.emprunts.create')" createLabel="Nouvel emprunt" />

    @php
        $filtres = ['' => 'Tous', 'en_cours' => 'En cours', 'retard' => 'En retard', 'rendu' => 'Rendus'];
    @endphp
    <div class="mb-4 flex gap-2 text-sm">
        @foreach ($filtres as $key => $label)
            <a href="{{ route('admin.emprunts.index', $key === '' ? [] : ['statut' => $key]) }}"
               class="px-3 py-1.5 rounded-md transition {{ (string) $statut === $key ? 'bg-blue-900 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Livre</th>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Emprunté le</th>
                    <th class="px-4 py-3">Retour prévu</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($emprunts as $e)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ optional($e->livre)->titre ?? '— supprimé —' }}</td>
                        <td class="px-4 py-3">{{ optional($e->eleve)->nom }} {{ optional($e->eleve)->prenom }}</td>
                        <td class="px-4 py-3">{{ optional($e->dateEmprunt)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ optional($e->dateRetourPrevue)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @if ($e->statut === 'rendu')
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    Rendu le {{ optional($e->dateRetourReelle)->format('d/m/Y') }}
                                </span>
                            @elseif ($e->en_retard)
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">En retard</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">En cours</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @if ($e->statut !== 'rendu')
                                <form method="POST" action="{{ route('admin.emprunts.retour', $e) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="text-emerald-600 hover:underline">Marquer rendu</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.emprunts.destroy', $e) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cet emprunt ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun emprunt.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $emprunts->links() }}</div>
</x-admin-layout>
