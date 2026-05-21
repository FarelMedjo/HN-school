<x-admin-layout>
    <x-page-header title="Années académiques" subtitle="Gestion des années scolaires"
                   :createRoute="route('admin.annees.create')" createLabel="Nouvelle année" />

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Libellé</th>
                    <th class="px-4 py-3">Période</th>
                    <th class="px-4 py-3">État</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($annees as $a)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $a->libelle }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $a->periode }}</td>
                        <td class="px-4 py-3">
                            @if ($a->actif)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs">Active</span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.annees.edit', $a) }}" class="text-sky-600 hover:underline">Éditer</a>
                            <form method="POST" action="{{ route('admin.annees.destroy', $a) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cette année ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucune année.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $annees->links() }}</div>
</x-admin-layout>
