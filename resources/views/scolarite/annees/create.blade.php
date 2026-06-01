<x-scolarite-layout>
    <x-page-header title="Nouvelle année académique" />
    @include('admin.annees._form', [
        'action' => route('scolarite.annees.store'),
        'method' => 'POST',
        'cancelUrl' => route('scolarite.annees.index'),
    ])
</x-scolarite-layout>
