<x-finance-layout>
    <x-page-header title="Paiements" :createRoute="route('finance.paiements.create')" createLabel="Enregistrer un paiement" />
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Montant</th>
                    <th class="px-4 py-3">Mode</th>
                    <th class="px-4 py-3">Année</th>
                    <th class="px-4 py-3">Commentaire</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($paiements as $p)
                    <tr>
                        <td class="px-4 py-3">{{ optional($p->datePaie)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-medium">{{ optional($p->eleve)->nom }} {{ optional($p->eleve)->prenom }}</td>
                        <td class="px-4 py-3">{{ number_format($p->montant, 0, ',', ' ') }} XAF</td>
                        <td class="px-4 py-3">{{ optional($p->mode)->libelle ?? '—' }}</td>
                        <td class="px-4 py-3">{{ optional($p->annee)->libelle ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($p->commentaire, 40) }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('finance.paiements.destroy', $p) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucun paiement.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $paiements->links() }}</div>
</x-finance-layout>
