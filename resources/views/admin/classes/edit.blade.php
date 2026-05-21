<x-admin-layout>
    <x-page-header title="Modifier la classe" />
    @include('admin.classes._form', ['action' => route('admin.classes.update', $classe), 'method' => 'PUT'])
</x-admin-layout>
