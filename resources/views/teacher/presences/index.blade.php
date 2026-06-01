<x-teacher-layout>
    <x-page-header
        title="{{ $cours->libelle }}"
        subtitle="Pointage · Classe {{ optional($cours->classe)->libelle ?? '—' }}" />

    {{-- Navigation du cours --}}
    <div class="flex gap-2 mb-6 text-sm">
        <a href="{{ route('teacher.cours.eleves', $cours) }}"
           class="px-4 py-2 rounded-md bg-white text-gray-600 hover:bg-gray-50 border">Élèves</a>
        <a href="{{ route('teacher.cours.presences', $cours) }}"
           class="px-4 py-2 rounded-md bg-indigo-600 text-white font-medium">Présences</a>
        <a href="{{ route('teacher.cours.notes', $cours) }}"
           class="px-4 py-2 rounded-md bg-white text-gray-600 hover:bg-gray-50 border">Notes</a>
    </div>

    {{-- Filtre date --}}
    <form method="GET" action="{{ route('teacher.cours.presences', $cours) }}"
          class="bg-white rounded-lg shadow-sm p-4 mb-6 flex items-end gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
            <input type="date" name="date" value="{{ $date }}"
                   class="border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        <button type="submit"
                class="px-4 py-2 bg-slate-700 text-white text-sm rounded-md hover:bg-slate-800 transition">
            Charger
        </button>
    </form>

    @if (!$salle)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-6">
            Aucune salle n'est associée à la classe de ce cours.
        </div>
    @elseif ($eleves->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Aucun élève affecté à cette classe pour l'année active.
        </div>
    @else
        <form method="POST" action="{{ route('teacher.cours.presences.store', $cours) }}">
            @csrf
            <input type="hidden" name="idSalle" value="{{ $salle->idSalle }}">
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <span class="text-sm text-gray-600">
                        <strong>{{ $eleves->count() }}</strong> élève(s) · {{ \Carbon\Carbon::parse($date)->isoFormat('dddd D MMMM YYYY') }}
                        <span class="ml-2 text-xs text-gray-400">Salle : {{ $salle->libelle }}</span>
                    </span>
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
                            <th class="px-4 py-3 w-10">#</th>
                            <th class="px-4 py-3">Élève</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Motif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($eleves as $i => $eleve)
                            @php
                                $p = $presences->get($eleve->matricule);
                                $statut = $p?->statut ?? 'present';
                                $colors = ['present' => 'emerald', 'absent' => 'rose', 'retard' => 'amber', 'justifie' => 'sky'];
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-medium">
                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach (\App\Models\Presence::STATUTS as $val => $label)
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio"
                                                       name="statuts[{{ $eleve->matricule }}]"
                                                       value="{{ $val }}"
                                                       class="statut-radio text-{{ $colors[$val] }}-600 border-gray-300"
                                                       @checked($statut === $val)>
                                                <span class="text-xs px-2 py-0.5 rounded bg-{{ $colors[$val] }}-50 text-{{ $colors[$val] }}-700 border border-{{ $colors[$val] }}-200">
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
                                           placeholder="Motif..."
                                           class="w-full border-gray-200 rounded text-xs shadow-sm @if($statut === 'present') opacity-30 @endif"
                                           @if($statut === 'present') disabled @endif>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Enregistrer le pointage
                </button>
            </div>
        </form>
    @endif

    <script>
        document.addEventListener('change', function (e) {
            if (!e.target.matches('.statut-radio')) return;
            const row = e.target.closest('tr');
            const motif = row.querySelector('input[type=text]');
            const isPresent = e.target.value === 'present';
            motif.disabled = isPresent;
            motif.classList.toggle('opacity-30', isPresent);
        });
        function setAll(val) {
            document.querySelectorAll(`.statut-radio[value="${val}"]`).forEach(r => {
                r.checked = true;
                r.dispatchEvent(new Event('change', { bubbles: true }));
            });
        }
    </script>
</x-teacher-layout>
