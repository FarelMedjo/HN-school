<x-parent-layout>
    <x-page-header
        title="{{ $eleve->prenom }} {{ $eleve->nom }}"
        subtitle="Profil · Classe {{ $classe ?? '—' }}" />

    {{-- Tabs --}}
    @include('parent.enfant._tabs', ['active' => 'profil', 'eleve' => $eleve])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {{-- Infos personnelles --}}
        <div class="bg-white rounded-lg shadow-sm p-6 space-y-3 text-sm">
            <h3 class="font-semibold text-gray-800 mb-3">Informations personnelles</h3>
            <div class="flex justify-between">
                <span class="text-gray-500">Matricule</span>
                <span class="font-medium">{{ str_pad($eleve->matricule, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Sexe</span>
                <span>{{ $eleve->sexe == 0 ? 'Masculin' : 'Féminin' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Date de naissance</span>
                <span>{{ optional($eleve->dateNaissance)->format('d/m/Y') ?? '—' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Lieu</span>
                <span>{{ $eleve->lieuNaissance ?? '—' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Langue</span>
                <span>{{ $eleve->langue ?? '—' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Classe</span>
                <span class="font-medium">{{ $classe ?? '—' }}</span>
            </div>
        </div>

        {{-- Stats synthèse --}}
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            @foreach ([
                ['Absences',          $nbAbsences,  'rose',    'Sur toute l\'année'],
                ['Retards',           $nbRetards,   'amber',   'Sur toute l\'année'],
                ['Évaluations',       $nbEvals,     'indigo',  'Notes enregistrées'],
                ['Payé (FCFA)',        number_format($totalPaye, 0, ',', ' '), 'emerald', 'Année en cours'],
            ] as [$label, $val, $color, $sub])
                <div class="bg-white rounded-lg shadow-sm p-5 text-center">
                    <div class="text-3xl font-bold text-{{ $color }}-600">{{ $val }}</div>
                    <div class="text-sm font-medium text-gray-700 mt-1">{{ $label }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">{{ $sub }}</div>
                </div>
            @endforeach
        </div>
    </div>
</x-parent-layout>
