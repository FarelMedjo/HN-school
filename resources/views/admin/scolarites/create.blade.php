<x-admin-layout>
    <x-page-header title="Nouvelle scolarité" />
    @include('admin.scolarites._form', ['action' => route('admin.scolarites.store'), 'method' => 'POST'])
</x-admin-layout>
