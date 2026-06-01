<x-student-layout>
    <x-page-header
        title="Mon emploi du temps"
        subtitle="Classe {{ $classe ?? '—' }}" />

    <div class="mt-6">
        <x-grille-edt
            :grille="$grille"
            :palette="$palette"
            :classeLibelle="$classe" />
    </div>
</x-student-layout>
