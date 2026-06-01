<x-scolarite-layout>
    <x-page-header title="Pointage des présences" />

    {{-- Filtres --}}
    <form method="GET" action="{{ route('scolarite.presences.index') }}"
          class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Salle / Classe</label>
            <select name="idSalle" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                @foreach ($salles as $s)
                    <option value="{{ $s->idSalle }}" @selected($s->idSalle == $idSalle)>
                        {{ $s->libelle }} — {{ optional($s->classe)->libelle ?? '—' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
            <input type="date" name="date" value="{{ $date }}"
                   class="border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        <button type="submit"
                class="px-4 py-2 bg-amber-800 text-white text-sm rounded-md hover:bg-amber-900 transition">
            Charger
        </button>
    </form>

    @if ($eleves->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Aucun élève affecté à cette salle pour l'année académique active.
        </div>
    @else
        <form method="POST" action="{{ route('scolarite.presences.store') }}">
            @csrf
            <input type="hidden" name="idSalle" value="{{ $idSalle }}">
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <span class="text-sm text-gray-600">
                        <strong>{{ $eleves->count() }}</strong> élève(s) · {{ \Carbon\Carbon::parse($date)->isoFormat('dddd D MMMM YYYY') }}
                    </span>
                    {{-- Sélection rapide --}}
                    <div class="flex gap-2 text-xs">
                        <button type="button" onclick="setAll('present')"
                                class="px-3 py-1 rounded bg-emerald-100 text-emerald-700 hover:bg-emerald-200">
                            Tous présents
                        </button>
                        <button type="button" onclick="setAll('absent')"
                                class="px-3 py-1 rounded bg-rose-100 text-rose-700 hover:bg-rose-200">
                            Tous absents
                        </button>
                    </div>
                </div>

                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
                        <tr>
                            <th class="px-4 py-3 w-12">#</th>
                            <th class="px-4 py-3">Élève</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Motif (facultatif)</th>
                            <th class="px-4 py-3">Historique</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="eleves-table">
                        @foreach ($eleves as $i => $eleve)
                            @php
                                $p = $presences->get($eleve->matricule);
                                $statut = $p?->statut ?? 'present';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-medium">
                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                    <div class="text-xs text-gray-400">Mat. {{ str_pad($eleve->matricule, 5, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach (\App\Models\Presence::STATUTS as $val => $label)
                                            @php
                                                $colors = [
                                                    'present'  => 'emerald',
                                                    'absent'   => 'rose',
                                                    'retard'   => 'amber',
                                                    'justifie' => 'sky',
                                                ];
                                                $c = $colors[$val];
                                            @endphp
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio"
                                                       name="statuts[{{ $eleve->matricule }}]"
                                                       value="{{ $val }}"
                                                       class="statut-radio text-{{ $c }}-600 border-gray-300"
                                                       @checked($statut === $val)>
                                                <span class="text-xs px-2 py-0.5 rounded bg-{{ $c }}-50 text-{{ $c }}-700 border border-{{ $c }}-200">
                                                    {{ $label }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text"
                                           name="motifs[{{ $eleve->matricule }}]"
                                           value="{{ $p?->motif }}"
                                           placeholder="Raison..."
                                           class="w-full border-gray-200 rounded text-xs shadow-sm @if($statut === 'present') opacity-30 @endif"
                                           @if($statut === 'present') disabled @endif>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('scolarite.presences.eleve', $eleve) }}"
                                       class="text-xs text-amber-700 hover:underline">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition">
                    Enregistrer le pointage
                </button>
            </div>
        </form>
    @endif

    <script>
        // Active/désactive le champ motif selon le statut choisi
        document.addEventListener('change', function (e) {
            if (!e.target.matches('.statut-radio')) return;
            const row = e.target.closest('tr');
            const motif = row.querySelector('input[type=text]');
            const isPresent = e.target.value === 'present';
            motif.disabled = isPresent;
            motif.classList.toggle('opacity-30', isPresent);
        });

        // Sélection rapide de tous les statuts
        function setAll(val) {
            document.querySelectorAll(`.statut-radio[value="${val}"]`).forEach(r => {
                r.checked = true;
                r.dispatchEvent(new Event('change', { bubbles: true }));
            });
        }
    </script>
</x-scolarite-layout>
