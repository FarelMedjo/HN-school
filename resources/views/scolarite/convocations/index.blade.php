<x-scolarite-layout>
    <x-page-header title="Convocations" :createRoute="route('scolarite.convocations.create')" createLabel="Nouvelle convocation" />

    <form method="GET" class="bg-white rounded-lg shadow-sm p-4 mb-4 flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[220px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Filtrer par élève</label>
            <select name="matricule" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                <option value="">— Tous les élèves —</option>
                @foreach ($eleves as $el)
                    <option value="{{ $el->matricule }}" @selected($matricule == $el->matricule)>{{ $el->nom }} {{ $el->prenom }}</option>
                @endforeach
            </select>
        </div>
        @if ($matricule)
            <a href="{{ route('scolarite.convocations.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Réinitialiser</a>
        @endif
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Objet</th>
                    <th class="px-4 py-3">Rendez-vous</th>
                    <th class="px-4 py-3">Lieu</th>
                    <th class="px-4 py-3">Auteur</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($convocations as $c)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ optional($c->eleve)->nom }} {{ optional($c->eleve)->prenom }}</td>
                        <td class="px-4 py-3">{{ $c->objet }}</td>
                        <td class="px-4 py-3">{{ $c->dateRdv->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $c->lieu ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($c->auteur)->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('scolarite.convocations.show', $c) }}" target="_blank" class="text-blue-600 hover:underline">Imprimer</a>
                            <form method="POST" action="{{ route('scolarite.convocations.destroy', $c) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cette convocation ?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune convocation.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $convocations->links() }}</div>
</x-scolarite-layout>
