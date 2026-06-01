<x-parent-layout>
    <x-page-header
        title="{{ $eleve->prenom }} {{ $eleve->nom }}"
        subtitle="Vie scolaire · Classe {{ $classe ?? '—' }}" />

    @include('parent.enfant._tabs', ['active' => 'vie', 'eleve' => $eleve])

    <div class="mt-6">
        <x-vie-scolaire
            :convocations="$convocations"
            :appreciations="$appreciations"
            :remarques="$remarques"
            printRouteName="parent.convocations.show" />
    </div>
</x-parent-layout>
