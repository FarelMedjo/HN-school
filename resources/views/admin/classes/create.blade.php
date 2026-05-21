<x-admin-layout>
    <x-page-header title="Nouvelle classe" />
    @include('admin.classes._form', ['action' => route('admin.classes.store'), 'method' => 'POST'])
</x-admin-layout>
