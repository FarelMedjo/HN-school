<x-admin-layout>
    <x-page-header title="Modifier le cours" />
    @include('admin.cours._form', ['action' => route('admin.cours.update', $cours), 'method' => 'PUT'])
</x-admin-layout>
