<x-admin-layout>
    <x-page-header title="Modifier la salle" />
    @include('admin.salles._form', ['action' => route('admin.salles.update', $salle), 'method' => 'PUT'])
</x-admin-layout>
