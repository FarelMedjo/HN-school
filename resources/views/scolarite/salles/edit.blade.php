<x-scolarite-layout>
    <x-page-header title="Modifier la salle" />
    @include('admin.salles._form', [
        'action' => route('scolarite.salles.update', $salle),
        'method' => 'PUT',
        'cancelUrl' => route('scolarite.salles.index'),
    ])
</x-scolarite-layout>
