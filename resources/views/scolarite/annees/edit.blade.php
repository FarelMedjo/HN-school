<x-scolarite-layout>
    <x-page-header title="Modifier l'année académique" />
    @include('admin.annees._form', [
        'action' => route('scolarite.annees.update', $annee),
        'method' => 'PUT',
        'cancelUrl' => route('scolarite.annees.index'),
    ])
</x-scolarite-layout>
