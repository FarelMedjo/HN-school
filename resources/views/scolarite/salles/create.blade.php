<x-scolarite-layout>
    <x-page-header title="Nouvelle salle" />
    @include('admin.salles._form', [
        'action' => route('scolarite.salles.store'),
        'method' => 'POST',
        'cancelUrl' => route('scolarite.salles.index'),
    ])
</x-scolarite-layout>
