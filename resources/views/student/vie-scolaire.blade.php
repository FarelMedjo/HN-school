<x-student-layout>
    <x-page-header
        title="Vie scolaire"
        subtitle="Convocations, appréciations et journal de suivi · Classe {{ $classe ?? '—' }}" />

    <div class="mt-6">
        <x-vie-scolaire
            :convocations="$convocations"
            :appreciations="$appreciations"
            :remarques="$remarques"
            printRouteName="student.convocations.show" />
    </div>
</x-student-layout>
