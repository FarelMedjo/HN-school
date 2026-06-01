<x-student-layout>
    <x-page-header
        title="Mes notes"
        subtitle="Classe {{ $classe ?? '—' }}" />

    <div class="mt-6">
        @if ($cours->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                Aucune matière enregistrée pour votre classe.
            </div>
        @else
            {{-- Résumé moyenne --}}
            @if ($moyenne !== null)
                @php $m = \App\Support\Notation::mention($moyenne, $section); @endphp
                <div class="mb-4 bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
                    <div class="text-center px-6 border-r border-gray-100">
                        <div class="text-3xl font-bold text-{{ $m['color'] }}-600">{{ number_format($moyenne, 2) }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Moyenne / 20</div>
                    </div>
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-{{ $m['color'] }}-100 text-{{ $m['color'] }}-700 text-sm font-semibold">
                            {{ $m['code'] }} — {{ $m['libelle'] }}
                        </span>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ ucfirst($section) }} ·
                            {{ $evaluations->count() }}/{{ $cours->count() }} matières notées
                        </div>
                    </div>
                </div>
            @endif

            {{-- Lien bulletin --}}
            <div class="mb-4">
                <a href="{{ route('student.bulletin') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700 transition">
                    📄 Voir mon bulletin
                </a>
            </div>

            {{-- Table des notes --}}
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
                        <tr>
                            <th class="px-4 py-3">Matière</th>
                            <th class="px-4 py-3 text-center">Coeff.</th>
                            <th class="px-4 py-3 text-center">Note</th>
                            <th class="px-4 py-3">Mention</th>
                            <th class="px-4 py-3">Appréciation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($cours as $c)
                            @php
                                $eval    = $evaluations->get($c->idCours);
                                $mention = $eval ? \App\Support\Notation::mention($eval->note, $section) : null;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">{{ $c->libelle }}</td>
                                <td class="px-4 py-3 text-center text-gray-500">{{ $c->coefficient }}</td>
                                <td class="px-4 py-3 text-center font-semibold">
                                    @if ($eval)
                                        {{ number_format($eval->note, 2) }} / {{ $c->note }}
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($mention)
                                        <span class="inline-block px-2 py-0.5 rounded text-xs bg-{{ $mention['color'] }}-100 text-{{ $mention['color'] }}-700">
                                            {{ $mention['code'] }} · {{ $mention['libelle'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs">Non noté</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $eval?->appreciation ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-student-layout>
