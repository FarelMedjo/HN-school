<x-parent-layout>
    <x-page-header
        title="{{ $eleve->prenom }} {{ $eleve->nom }}"
        subtitle="Absences & Présences · Classe {{ $classe ?? '—' }}" />

    @include('parent.enfant._tabs', ['active' => 'presences', 'eleve' => $eleve])

    <div class="mt-6">
        {{-- Compteurs --}}
        @php
            $colorMap = ['present' => 'emerald', 'absent' => 'rose', 'retard' => 'amber', 'justifie' => 'sky'];
            $labelMap = ['present' => 'Présent', 'absent' => 'Absent', 'retard' => 'Retard', 'justifie' => 'Justifié'];
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            @foreach ($colorMap as $statut => $color)
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-3xl font-bold text-{{ $color }}-600">{{ $counts[$statut] ?? 0 }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $labelMap[$statut] }}</div>
                </div>
            @endforeach
        </div>

        {{-- Historique --}}
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
                        @php $color = $colorMap[$p->statut] ?? 'gray'; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $p->date->isoFormat('ddd D MMM YYYY') }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ optional($p->salle)->libelle ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded text-xs bg-{{ $color }}-100 text-{{ $color }}-700">
                                    {{ $labelMap[$p->statut] ?? $p->statut }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $p->motif ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                Aucun enregistrement de présence.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $presences->links() }}</div>
    </div>
</x-parent-layout>
