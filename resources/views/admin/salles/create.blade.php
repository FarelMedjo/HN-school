<x-admin-layout>
    <x-page-header title="Nouvelle salle" />
    @include('admin.salles._form', ['action' => route('admin.salles.store'), 'method' => 'POST'])
</x-admin-layout>
