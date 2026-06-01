@php
    $tabs = [
        ['profil',     'Profil',          'parent.enfant.show'],
        ['notes',      'Notes',           'parent.enfant.notes'],
        ['presences',  'Absences',        'parent.enfant.presences'],
        ['paiements',  'Paiements',       'parent.enfant.paiements'],
        ['edt',        'Emploi du temps', 'parent.enfant.emploi-du-temps'],
        ['emprunts',   'Emprunts',        'parent.enfant.emprunts'],
        ['vie',        'Vie scolaire',    'parent.enfant.vie-scolaire'],
    ];
@endphp
<div class="flex gap-1 border-b border-gray-200">
    @foreach ($tabs as [$key, $label, $route])
        <a href="{{ route($route, $eleve) }}"
           class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition
                  {{ $active === $key
                      ? 'border-teal-600 text-teal-700'
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            {{ $label }}
        </a>
    @endforeach
</div>
