<x-admin-layout>
    <x-page-header
        title="Absences — {{ $eleve->nom }} {{ $eleve->prenom }}"
        subtitle="Mat. {{ str_pad($eleve->matricule, 5, '0', STR_PAD_LEFT) }}" />

    @php
        $counts = $presences->getCollection()->groupBy('statut');
        $colors = [
            'present'  => ['bg' => 'emerald', 'label' => 'Présent'],
            'absent'   => ['bg' => 'rose',    'label' => 'Absent'],
            'retard'   => ['bg' => 'amber',   'label' => 'Retard'],
            'justifie' => ['bg' => 'sky',     'label' => 'Justifié'],
        ];
    @endphp

    {{-- Résumé stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @foreach ($colors as $key => $cfg)
            <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                <div class="text-3xl font-bold text-{{ $cfg['bg'] }}-600">
                    {{ $counts->get($key)?->count() ?? 0 }}
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ $cfg['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Table historique --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Salle</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Motif</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($presences as $p)
                    @php $cfg = $colors[$p->statut] ?? ['bg' => 'gray', 'label' => $p->statut]; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $p->date->isoFormat('ddd D MMM YYYY') }}</td>
                        <td class="px-4 py-3">{{ optional($p->salle)->libelle ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 rounded text-xs bg-{{ $cfg['bg'] }}-100 text-{{ $cfg['bg'] }}-700">
                                {{ $cfg['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->motif ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            Aucun enregistrement de présence pour cet élève.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex items-center justify-between">
        <a href="{{ route('admin.presences.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Retour au pointage</a>
        <div>{{ $presences->links() }}</div>
    </div>
</x-admin-layout>
