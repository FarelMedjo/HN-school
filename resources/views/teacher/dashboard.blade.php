<x-teacher-layout>
    <x-page-header title="Tableau de bord" />

    @if (!$pers)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-6">
            <strong>Profil non lié.</strong> Votre compte n'est pas encore associé à une fiche enseignant.
            Contactez l'administrateur.
        </div>
    @else
        {{-- Cartes de synthèse --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach ([
                ['Mes cours', $cours->count(), 'indigo', 'Cours assignés'],
                ['Élèves', $nbEleves, 'sky', 'Dans mes classes'],
                ['Absences', $nbAbsences, 'rose', 'Cette semaine'],
                ['Notes saisies', $nbNotes, 'emerald', 'Évaluations enregistrées'],
            ] as [$label, $value, $color, $sub])
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <div class="text-3xl font-bold text-{{ $color }}-600">{{ $value }}</div>
                    <div class="text-sm font-medium text-gray-700 mt-1">{{ $label }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">{{ $sub }}</div>
                </div>
            @endforeach
        </div>

        {{-- Liste des cours --}}
        <h2 class="text-base font-semibold text-gray-700 mb-3">Mes cours</h2>
        @if ($cours->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                Aucun cours ne vous est encore assigné.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($cours as $c)
                    <div class="bg-white rounded-lg shadow-sm p-5 flex flex-col gap-3">
                        <div>
                            <div class="font-semibold text-gray-800">{{ $c->libelle }}</div>
                            <div class="text-sm text-gray-500">
                                Classe : {{ optional($c->classe)->libelle ?? '—' }}
                                · Coeff. {{ $c->coefficient }}
                                · /{{ $c->note }}
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap text-xs">
                            <a href="{{ route('teacher.cours.eleves', $c) }}"
                               class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition">
                                Élèves
                            </a>
                            <a href="{{ route('teacher.cours.presences', $c) }}"
                               class="px-3 py-1.5 bg-sky-50 text-sky-700 rounded hover:bg-sky-100 transition">
                                Présences
                            </a>
                            <a href="{{ route('teacher.cours.notes', $c) }}"
                               class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition">
                                Notes
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</x-teacher-layout>
