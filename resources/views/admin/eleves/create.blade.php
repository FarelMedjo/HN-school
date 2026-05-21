<x-admin-layout>
    <x-page-header title="Nouvel élève" />
    @include('admin.eleves._form', ['action' => route('admin.eleves.store'), 'method' => 'POST'])
</x-admin-layout>
