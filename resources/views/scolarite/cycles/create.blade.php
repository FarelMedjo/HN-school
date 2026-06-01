<x-scolarite-layout>
    <x-page-header title="Nouveau cycle" />
    @include('admin.cycles._form', [
        'action' => route('scolarite.cycles.store'),
        'method' => 'POST',
        'cancelUrl' => route('scolarite.cycles.index'),
    ])
</x-scolarite-layout>
