<x-scolarite-layout>
    <x-page-header title="Nouvel élève" />
    @include('admin.eleves._form', [
        'action' => route('scolarite.eleves.store'),
        'method' => 'POST',
        'cancelUrl' => route('scolarite.eleves.index'),
    ])
</x-scolarite-layout>
