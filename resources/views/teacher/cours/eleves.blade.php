<x-teacher-layout>
    <x-page-header
        title="{{ $cours->libelle }}"
        subtitle="Élèves · Classe {{ optional($cours->classe)->libelle ?? '—' }}" />

    {{-- Navigation du cours --}}
    <div class="flex gap-2 mb-6 text-sm">
        <a href="{{ route('teacher.cours.eleves', $cours) }}"
           class="px-4 py-2 rounded-md bg-indigo-600 text-white font-medium">Élèves</a>
        <a href="{{ route('teacher.cours.presences', $cours) }}"
           class="px-4 py-2 rounded-md bg-white text-gray-600 hover:bg-gray-50 border">Présences</a>
        <a href="{{ route('teacher.cours.notes', $cours) }}"
           class="px-4 py-2 rounded-md bg-white text-gray-600 hover:bg-gray-50 border">Notes</a>
    </div>

    @if ($eleves->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Aucun élève affecté à cette classe pour l'année active.
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Élève</th>
                        <th class="px-4 py-3">Présences</th>
                        <th class="px-4 py-3">Absences</th>
                        <th class="px-4 py-3">Note</th>
                        <th class="px-4 py-3">Mention</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($eleves as $i => $eleve)
                        @php
                            $stats  = $presenceStats->get($eleve->matricule) ?? collect();
                            $nbPres = $stats->where('statut', 'present')->sum('total');
                            $nbAbs  = $stats->where('statut', 'absent')->sum('total');
                            $eval   = $notes->get($eleve->matricule);
                            $section = $cours->classe?->section ?? 'francophone';
                            $mention = $eval ? \App\Support\Notation::mention($eval->note, $section) : null;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $eleve->nom }} {{ $eleve->prenom }}</div>
                                <div class="text-xs text-gray-400">Mat. {{ str_pad($eleve->matricule, 5, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-4 py-3 text-emerald-600 font-medium">{{ $nbPres }}</td>
                            <td class="px-4 py-3 text-rose-600 font-medium">{{ $nbAbs }}</td>
                            <td class="px-4 py-3 font-medium">
                                {{ $eval ? number_format($eval->note, 2) . ' / ' . $cours->note : '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($mention)
                                    <span class="inline-block px-2 py-0.5 rounded text-xs bg-{{ $mention['color'] }}-100 text-{{ $mention['color'] }}-700">
                                        <strong>{{ $mention['code'] }}</strong> · {{ $mention['libelle'] }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-teacher-layout>
