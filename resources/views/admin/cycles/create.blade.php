<x-admin-layout>
    <x-page-header title="Nouveau cycle" />
    @include('admin.cycles._form', ['action' => route('admin.cycles.store'), 'method' => 'POST'])
</x-admin-layout>
