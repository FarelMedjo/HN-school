<x-admin-layout>
    <x-page-header title="Horaires" subtitle="Tranches horaires de l'emploi du temps" />

    <div class="flex justify-start mb-4">
        <a href="{{ route('admin.emploi-du-temps.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition">
            ← Retour à l'emploi du temps
        </a>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        {{-- Ajout --}}
        <div class="bg-white rounded-lg shadow-sm p-4 h-fit">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Ajouter une tranche</h3>
            <form method="POST" action="{{ route('admin.horaires.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Heure de début</label>
                    <input type="time" name="heure" required
                           value="{{ old('heure') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                </div>
                <button type="submit"
                        class="w-full py-2 bg-blue-950 text-white text-sm rounded-md hover:bg-blue-900 transition">
                    Ajouter
                </button>
            </form>
        </div>

        {{-- Liste --}}
        <div class="md:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
            @if ($horaires->isEmpty())
                <div class="p-8 text-center text-gray-500 text-sm">
                    Aucune tranche horaire. La grille utilise les horaires par défaut tant qu'aucune n'est définie.
                </div>
            @else
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-2 text-xs font-semibold text-gray-500">Heure</th>
                            <th class="px-4 py-2 text-xs font-semibold text-gray-500">Créneaux rattachés</th>
                            <th class="px-4 py-2 text-xs font-semibold text-gray-500 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($horaires as $h)
                            @php $count = $usage[$h->heure] ?? 0; @endphp
                            <tr>
                                <td class="px-4 py-2 font-medium text-gray-800">{{ $h->heure }}</td>
                                <td class="px-4 py-2 text-gray-500">
                                    {{ $count }} créneau(x)
                                </td>
                                <td class="px-4 py-2 text-right">
                                    @if ($count > 0)
                                        <span class="text-xs text-gray-400" title="Supprimez d'abord les créneaux">
                                            verrouillée
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('admin.horaires.destroy', $h) }}"
                                              onsubmit="return confirm('Supprimer la tranche {{ $h->heure }} ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="text-rose-600 hover:text-rose-800 text-xs font-medium">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <p class="text-xs text-gray-400 mt-4">
        Les tranches sont triées automatiquement par heure. Une tranche utilisée par des cours ne peut pas être supprimée.
    </p>
</x-admin-layout>
