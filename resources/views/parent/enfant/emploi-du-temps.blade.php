<x-parent-layout>
    <x-page-header
        title="{{ $eleve->prenom }} {{ $eleve->nom }}"
        subtitle="Emploi du temps · Classe {{ $classe ?? '—' }}" />

    @include('parent.enfant._tabs', ['active' => 'edt', 'eleve' => $eleve])

    <div class="mt-6">
        <x-grille-edt
            :grille="$grille"
            :palette="$palette"
            :classeLibelle="$classe" />
    </div>
</x-parent-layout>
