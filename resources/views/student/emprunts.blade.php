<x-student-layout>
    <x-page-header
        title="Mes emprunts"
        subtitle="Bibliothèque · Classe {{ $classe ?? '—' }}" />

    <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Livre</th>
                    <th class="px-4 py-3">Auteur</th>
                    <th class="px-4 py-3">Emprunté le</th>
                    <th class="px-4 py-3">Retour prévu</th>
                    <th class="px-4 py-3">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($emprunts as $e)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ optional($e->livre)->titre ?? '— supprimé —' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($e->livre)->auteur ?? '—' }}</td>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            Aucun emprunt enregistré.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $emprunts->links() }}</div>
</x-student-layout>
