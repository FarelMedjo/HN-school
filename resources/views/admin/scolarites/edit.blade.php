<x-admin-layout>
    <x-page-header title="Modifier la scolarité" />
    @include('admin.scolarites._form', ['action' => route('admin.scolarites.update', $scolarite), 'method' => 'PUT'])
</x-admin-layout>
