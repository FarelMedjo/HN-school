<x-admin-layout>
    <x-page-header title="Modifier l'élève" />
    @include('admin.eleves._form', ['action' => route('admin.eleves.update', $eleve), 'method' => 'PUT'])
</x-admin-layout>
