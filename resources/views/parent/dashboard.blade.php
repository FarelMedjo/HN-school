<x-parent-layout>
    <x-page-header title="Tableau de bord" />

    @if (!$pers)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-6">
            <strong>Profil non lié.</strong> Votre compte n'est pas encore associé à une fiche parent.
            Contactez l'administration.
        </div>
    @elseif ($enfants->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Aucun enfant n'est encore rattaché à votre compte.
        </div>
    @else
        <p class="text-sm text-gray-500 mb-6">
            Bonjour <strong>{{ $pers->prenom }} {{ $pers->nom }}</strong> —
            vous suivez {{ $enfants->count() }} enfant(s).
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($enfants as $e)
                @php
                    $s = $stats[$e->matricule] ?? ['absences' => 0, 'totalPaye' => 0];
                    $f = $e->frequentations->first();
                    $classe = optional(optional($f?->salle)->classe)->libelle ?? '—';
                @endphp
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    {{-- En-tête carte enfant --}}
                    <div class="bg-teal-700 px-5 py-4 flex items-center justify-between">
                        <div>
                            <div class="text-white font-semibold text-base">{{ $e->prenom }} {{ $e->nom }}</div>
                            <div class="text-teal-200 text-xs mt-0.5">
                                Classe : {{ $classe }} · Mat. {{ str_pad($e->matricule, 5, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div class="text-3xl">{{ $e->sexe == 0 ? '👦' : '👧' }}</div>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 divide-x divide-gray-100">
                        <div class="px-5 py-4 text-center">
                            <div class="text-2xl font-bold text-rose-500">{{ $s['absences'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">Absences cette semaine</div>
                        </div>
                        <div class="px-5 py-4 text-center">
                            <div class="text-2xl font-bold text-emerald-600">
                                {{ number_format($s['totalPaye'], 0, ',', ' ') }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">FCFA payés (année)</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="border-t border-gray-100 px-5 py-3 flex gap-3 text-xs flex-wrap">
                        <a href="{{ route('parent.enfant.show', $e) }}"
                           class="px-3 py-1.5 bg-teal-50 text-teal-700 rounded hover:bg-teal-100">Profil</a>
                        <a href="{{ route('parent.enfant.notes', $e) }}"
                           class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100">Notes</a>
                        <a href="{{ route('parent.enfant.presences', $e) }}"
                           class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded hover:bg-amber-100">Absences</a>
                        <a href="{{ route('parent.enfant.paiements', $e) }}"
                           class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100">Paiements</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-parent-layout>
