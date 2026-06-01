<x-teacher-layout>
    <x-page-header title="Emploi du temps" subtitle="Mes classes" />

    @if ($classes->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Aucun cours assigné — emploi du temps indisponible.
        </div>
    @else
        @foreach ($classes as $classe)
            <div class="mb-8">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">
                    Classe : {{ $classe['libelle'] }}
                </h2>
                <x-grille-edt
                    :grille="$classe['grille']"
                    :palette="$classe['palette']"
                    :classeLibelle="$classe['libelle']" />
            </div>
        @endforeach
    @endif
</x-teacher-layout>
