<x-admin-layout>
    <x-page-header title="Modifier le livre" />
    @include('admin.livres._form', ['action' => route('admin.livres.update', $livre), 'method' => 'PUT'])
</x-admin-layout>
