<x-admin-layout>
    <x-page-header title="Modifier l'année" />
    @include('admin.annees._form', ['action' => route('admin.annees.update', $annee), 'method' => 'PUT'])
</x-admin-layout>
