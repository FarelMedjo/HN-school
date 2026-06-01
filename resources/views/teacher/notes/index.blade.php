<x-teacher-layout>
    <x-page-header
        title="{{ $cours->libelle }}"
        subtitle="Saisie des notes · /{{ $cours->note }} · Coeff. {{ $cours->coefficient }}" />

    {{-- Navigation du cours --}}
    <div class="flex gap-2 mb-6 text-sm">
        <a href="{{ route('teacher.cours.eleves', $cours) }}"
           class="px-4 py-2 rounded-md bg-white text-gray-600 hover:bg-gray-50 border">Élèves</a>
        <a href="{{ route('teacher.cours.presences', $cours) }}"
           class="px-4 py-2 rounded-md bg-white text-gray-600 hover:bg-gray-50 border">Présences</a>
        <a href="{{ route('teacher.cours.notes', $cours) }}"
           class="px-4 py-2 rounded-md bg-indigo-600 text-white font-medium">Notes</a>
    </div>

    @if ($eleves->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Aucun élève affecté à cette classe pour l'année active.
        </div>
    @else
        <form method="POST" action="{{ route('teacher.cours.notes.store', $cours) }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 text-sm text-gray-600">
                    <strong>{{ $eleves->count() }}</strong> élève(s) — saisir une note sur <strong>{{ $cours->note }}</strong>
                    @php $section = $cours->classe?->section ?? 'francophone'; @endphp
                    <span class="ml-2 text-xs text-gray-400">Système {{ ucfirst($section) }}</span>
                </div>

                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
                        <tr>
                            <th class="px-4 py-3 w-10">#</th>
                            <th class="px-4 py-3">Élève</th>
                            <th class="px-4 py-3 w-36">Note /{{ $cours->note }}</th>
                            <th class="px-4 py-3">Mention</th>
                            <th class="px-4 py-3">Appréciation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="notes-table">
                        @foreach ($eleves as $i => $eleve)
                            @php
                                $eval = $evaluations->get($eleve->matricule);
                                $note = $eval?->note;
                                $mention = $note !== null ? \App\Support\Notation::mention($note, $section) : null;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-medium">
                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number"
                                           name="notes[{{ $eleve->matricule }}]"
                                           value="{{ $note }}"
                                           min="0" max="{{ $cours->note }}" step="0.25"
                                           placeholder="—"
                                           data-max="{{ $cours->note }}"
                                           data-section="{{ $section }}"
                                           data-row="{{ $eleve->matricule }}"
                                           class="note-input w-24 border-gray-300 rounded text-sm shadow-sm text-center"
                                           oninput="updateMention(this)">
                                </td>
                                <td class="px-4 py-3" id="mention-{{ $eleve->matricule }}">
                                    @if ($mention)
                                        <span class="inline-block px-2 py-0.5 rounded text-xs bg-{{ $mention['color'] }}-100 text-{{ $mention['color'] }}-700">
                                            {{ $mention['code'] }} · {{ $mention['libelle'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text"
                                           name="appreciations[{{ $eleve->matricule }}]"
                                           value="{{ $eval?->appreciation }}"
                                           placeholder="Appréciation..."
                                           class="w-full border-gray-200 rounded text-xs shadow-sm">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Enregistrer les notes
                </button>
            </div>
        </form>
    @endif

    {{-- Mention live preview --}}
    <script>
        const mentionsFr = [
            [16, 'EX', 'Excellent', 'emerald'],
            [14, 'TB', 'Très Bien', 'green'],
            [12, 'B',  'Bien', 'sky'],
            [10, 'AB', 'Assez Bien', 'indigo'],
            [8,  'P',  'Passable', 'amber'],
            [0,  'F',  'Faible', 'rose'],
        ];
        const mentionsEn = [
            [18, 'A+', 'Outstanding', 'emerald'],
            [16, 'A',  'Excellent', 'green'],
            [14, 'B+', 'Very Good', 'sky'],
            [12, 'B',  'Good', 'indigo'],
            [10, 'C',  'Average', 'amber'],
            [8,  'D',  'Below average', 'orange'],
            [0,  'F',  'Fail', 'rose'],
        ];

        function getMention(note, max, section) {
            const scaled = note * 20 / max; // normalise sur 20
            const table = section === 'anglophone' ? mentionsEn : mentionsFr;
            for (const [min, code, libelle, color] of table) {
                if (scaled >= min) return { code, libelle, color };
            }
            return { code: '—', libelle: '—', color: 'gray' };
        }

        function updateMention(input) {
            const note = parseFloat(input.value);
            const max  = parseFloat(input.dataset.max) || 20;
            const section = input.dataset.section || 'francophone';
            const id   = input.dataset.row;
            const cell = document.getElementById('mention-' + id);
            if (!cell) return;
            if (isNaN(note) || input.value === '') {
                cell.innerHTML = '<span class="text-gray-400 text-xs">—</span>';
                return;
            }
            const m = getMention(note, max, section);
            cell.innerHTML = `<span class="inline-block px-2 py-0.5 rounded text-xs bg-${m.color}-100 text-${m.color}-700">${m.code} · ${m.libelle}</span>`;
        }
    </script>
</x-teacher-layout>
