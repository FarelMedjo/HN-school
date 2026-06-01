<x-student-layout>
    <x-page-header
        title="{{ $eleve->prenom }} {{ $eleve->nom }}"
        subtitle="Classe {{ $classe ?? '—' }} · {{ $annee->libelle ?? '' }}" />

    {{-- Cartes stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-lg">
                {{ $nbNotes }}
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Notes saisies</div>
                <div class="text-xl font-semibold text-gray-800">{{ $nbNotes }}</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 font-bold text-lg">
                {{ $nbAbsences }}
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Absences</div>
                <div class="text-xl font-semibold text-gray-800">{{ $nbAbsences }}</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-lg">
                {{ $nbRetards }}
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Retards</div>
                <div class="text-xl font-semibold text-gray-800">{{ $nbRetards }}</div>
            </div>
        </div>
    </div>

    {{-- Raccourcis --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ([
            ['Mes notes',       'student.notes',           'purple'],
            ['Mes absences',    'student.absences',        'rose'],
            ['Emploi du temps', 'student.emploi-du-temps', 'blue'],
            ['Mon bulletin',    'student.bulletin',        'amber'],
        ] as [$label, $route, $color])
            <a href="{{ route($route) }}"
               class="block bg-white border border-{{ $color }}-200 rounded-lg p-5 shadow-sm hover:shadow-md transition group">
                <div class="text-sm font-semibold text-gray-700 group-hover:text-{{ $color }}-700">{{ $label }}</div>
                <div class="mt-1 text-xs text-gray-400 group-hover:text-{{ $color }}-500">Consulter →</div>
            </a>
        @endforeach
    </div>
</x-student-layout>
