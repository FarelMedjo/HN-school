<x-finance-layout>
    <x-page-header title="Modes de paiement" :createRoute="route('finance.modes.create')" createLabel="Nouveau mode" />
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Libellé</th>
                    <th class="px-4 py-3">Information</th>
                    <th class="px-4 py-3">Actif</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($modes as $m)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $m->libelle }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($m->information, 50) }}</td>
                        <td class="px-4 py-3">{!! $m->actif ? '✅' : '—' !!}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('finance.modes.edit', $m) }}" class="text-sky-600 hover:underline">Éditer</a>
                            <form method="POST" action="{{ route('finance.modes.destroy', $m) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucun mode de paiement.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $modes->links() }}</div>
</x-finance-layout>
