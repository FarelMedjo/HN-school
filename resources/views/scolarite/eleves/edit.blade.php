<x-scolarite-layout>
    <x-page-header title="Modifier l'élève" />
    @include('admin.eleves._form', [
        'action' => route('scolarite.eleves.update', $eleve),
        'method' => 'PUT',
        'cancelUrl' => route('scolarite.eleves.index'),
    ])
</x-scolarite-layout>
