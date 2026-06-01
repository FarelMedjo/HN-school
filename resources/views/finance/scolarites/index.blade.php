<x-finance-layout>
    <x-page-header title="Grille tarifaire" :createRoute="route('finance.scolarites.create')" createLabel="Nouveaux frais" />
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Cycle</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Inscription</th>
                    <th class="px-4 py-3">Pension</th>
                    <th class="px-4 py-3">Tranches</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($scolarites as $s)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ optional($s->cycle)->libelle ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($s->description, 40) }}</td>
                        <td class="px-4 py-3">{{ number_format($s->inscription, 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ number_format($s->pension, 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ $s->tranches->count() }} / {{ $s->nbreTranche }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('finance.scolarites.edit', $s) }}" class="text-sky-600 hover:underline">Éditer</a>
                            <form method="POST" action="{{ route('finance.scolarites.destroy', $s) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun frais défini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $scolarites->links() }}</div>
</x-finance-layout>
