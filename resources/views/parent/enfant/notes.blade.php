<x-parent-layout>
    <x-page-header
        title="{{ $eleve->prenom }} {{ $eleve->nom }}"
        subtitle="Notes · Classe {{ $classe ?? '—' }}" />

    @include('parent.enfant._tabs', ['active' => 'notes', 'eleve' => $eleve])

    {{-- Télécharger bulletin --}}
    @php $trimestres = \App\Models\Trimestre::with('annee')->get(); @endphp
    @if ($trimestres->isNotEmpty())
        <div class="mt-4 flex items-center gap-3 flex-wrap">
            <form method="GET" id="bulletin-dl" class="flex items-center gap-2">
                <select name="t" class="border-gray-300 rounded-md shadow-sm text-sm">
                    @foreach ($trimestres as $t)
                        <option value="{{ $t->idTrimes }}">
                            {{ $t->libelle }}{{ $t->annee ? ' · ' . $t->annee->libelle : '' }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="px-4 py-2 bg-teal-600 text-white text-sm rounded-md hover:bg-teal-700 transition">
                    📄 Télécharger le bulletin
                </button>
            </form>
        </div>
        <script>
            document.getElementById('bulletin-dl').addEventListener('submit', function(e) {
                e.preventDefault();
                const t = this.querySelector('[name=t]').value;
                window.open('{{ url("parent/enfant/" . $eleve->matricule . "/bulletin") }}/' + t, '_blank');
            });
        </script>
    @endif

    <div class="mt-6">
        @if ($cours->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                Aucune matière enregistrée pour cette classe.
            </div>
        @else
            {{-- Résumé --}}
            @php
                $notees = $cours->filter(fn($c) => $evaluations->has($c->idCours));
                $moyenne = $notees->isNotEmpty()
                    ? $notees->sum(fn($c) => ($evaluations[$c->idCours]->note / $c->note) * $c->coefficient)
                      / $notees->sum('coefficient') * 20
                    : null;
            @endphp
            @if ($moyenne !== null)
                <div class="mb-4 bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
                    @php $m = \App\Support\Notation::mention($moyenne, $section); @endphp
                    <div class="text-center px-6 border-r border-gray-100">
                        <div class="text-3xl font-bold text-{{ $m['color'] }}-600">{{ number_format($moyenne, 2) }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Moyenne / 20</div>
                    </div>
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-{{ $m['color'] }}-100 text-{{ $m['color'] }}-700 text-sm font-semibold">
                            {{ $m['code'] }} — {{ $m['libelle'] }}
                        </span>
                        <div class="text-xs text-gray-400 mt-1">{{ ucfirst($section) }} · {{ $notees->count() }}/{{ $cours->count() }} matières notées</div>
                    </div>
                </div>
            @endif

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
</x-parent-layout>
