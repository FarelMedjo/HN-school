<x-scolarite-layout>
    <x-page-header title="Modifier le cycle" />
    @include('admin.cycles._form', [
        'action' => route('scolarite.cycles.update', $cycle),
        'method' => 'PUT',
        'cancelUrl' => route('scolarite.cycles.index'),
    ])
</x-scolarite-layout>
