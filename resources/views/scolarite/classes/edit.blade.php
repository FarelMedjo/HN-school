<x-scolarite-layout>
    <x-page-header title="Modifier la classe" />
    @include('admin.classes._form', [
        'action' => route('scolarite.classes.update', $classe),
        'method' => 'PUT',
        'cancelUrl' => route('scolarite.classes.index'),
    ])
</x-scolarite-layout>
