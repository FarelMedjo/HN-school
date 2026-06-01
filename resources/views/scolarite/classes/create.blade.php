<x-scolarite-layout>
    <x-page-header title="Nouvelle classe" />
    @include('admin.classes._form', [
        'action' => route('scolarite.classes.store'),
        'method' => 'POST',
        'cancelUrl' => route('scolarite.classes.index'),
    ])
</x-scolarite-layout>
